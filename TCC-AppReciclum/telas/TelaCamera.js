
import React, { useRef, useState } from 'react';
import {
    View,
    Text,
    TouchableOpacity,
    StyleSheet,
    Image
} from 'react-native';

import { CameraView, useCameraPermissions } from 'expo-camera';
import * as ImagePicker from 'expo-image-picker';

export default function TelaCamera({navigation}) {

    const [permission, requestPermission] = useCameraPermissions();

    // Guarda a imagem escolhida ou tirada
    const [imagem, setImagem] = useState(null);

    const cameraRef = useRef(null);

    // ============================
    // ABRIR GALERIA
    // ============================
    async function abrirGaleria() {

        const resultado = await ImagePicker.launchImageLibraryAsync({
            mediaTypes: ['images'],
            allowsEditing: true,
            aspect: [4, 3],
            quality: 1,
        });

        if (!resultado.canceled) {
            setImagem(resultado.assets[0].uri);
        }
    }

    // ============================
    // TIRAR FOTO
    // ============================
    async function tirarFoto() {

        if (cameraRef.current) {

            const foto = await cameraRef.current.takePictureAsync();

            setImagem(foto.uri);
        }
    }

    // ============================
    // PERMISSÃO DA CÂMERA
    // ============================
    if (!permission) {
        return <View />;
    }

    if (!permission.granted) {

        return (
            <View style={styles.container}>

                <Text style={styles.texto}>
                    Precisamos de permissão para usar a câmera.
                </Text>

                <TouchableOpacity
                    style={styles.botao}
                    onPress={requestPermission}
                >
                    <Text style={styles.textoBotao}>
                        Permitir câmera
                    </Text>
                </TouchableOpacity>

            </View>
        );
    }

    // ============================
    // TELA PRINCIPAL
    // ============================
    return (
        <View style={styles.container}>

            {/* =========================
                SE NÃO TIVER IMAGEM
            ========================== */}

            {!imagem && (
                <CameraView
                    ref={cameraRef}
                    style={styles.camera}
                    facing="back"
                />
            )}

            {/* =========================
                SE TIVER IMAGEM
            ========================== */}

            {imagem && (
                <Image
                    source={{ uri: imagem }}
                    style={styles.imagem}
                />
            )}

            {/* =========================
                BOTÕES
            ========================== */}

            <View style={styles.areaBotoes}>

                {/* GALERIA */}

                <TouchableOpacity
                    style={styles.botaoGaleria}
                    onPress={abrirGaleria}
                >
                    <Text style={styles.icone}>
                        🖼️
                    </Text>

                    <Text style={styles.textoBotao}>
                        Galeria
                    </Text>
                </TouchableOpacity>


                {/* CÂMERA */}

                {!imagem && (

                    <TouchableOpacity
                        style={styles.botaoFoto}
                        onPress={tirarFoto}
                    >
                        <View style={styles.circulo} />
                    </TouchableOpacity>

                )}


                {/* TIRAR NOVA FOTO */}

                {imagem && (

                    <TouchableOpacity
                        style={styles.botaoNovaFoto}
                        onPress={() => setImagem(null)}
                    >
                        <Text style={styles.textoBotao}>
                            outra foto
                        </Text>
                    </TouchableOpacity>

                )}

            </View>

        </View>
    );
}


// ============================
// ESTILOS
// ============================

const styles = StyleSheet.create({

    container: {
        flex: 1,
        backgroundColor: '#000',
    },

    camera: {
        flex: 1,
    },

    imagem: {
        flex: 1,
        width: '100%',
        resizeMode: 'contain',
    },

    texto: {
        color: '#fff',
        textAlign: 'center',
        marginBottom: 20,
        fontSize: 16,
    },

    botao: {
        backgroundColor: '#4CAF50',
        padding: 15,
        marginHorizontal: 40,
        borderRadius: 10,
    },

    textoBotao: {
        color: '#fff',
        textAlign: 'center',
        fontSize: 16,
        fontWeight: 'bold',
    },

    areaBotoes: {
        position: 'absolute',
        bottom: 30,
        width: '100%',
        alignItems: 'center',
    },

    botaoFoto: {
        width: 80,
        height: 80,
        borderRadius: 40,
        backgroundColor: '#fff',
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 30,
    },

    circulo: {
        width: 65,
        height: 65,
        borderRadius: 35,
        backgroundColor: '#ddd',
    },

    botaoGaleria: {
        position: 'absolute',
        left: 25,
        bottom: 30,
        alignItems: 'center',
    },

    icone: {
        fontSize: 30,
    },

    botaoNovaFoto: {
        backgroundColor: '#4CAF50',
        paddingVertical: 12,
        paddingHorizontal: 20,
        borderRadius: 10,
    },

});
