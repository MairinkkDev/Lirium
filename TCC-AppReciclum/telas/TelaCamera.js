import React, { useCallback, useRef, useState } from 'react';
import {
    View,
    Text,
    TouchableOpacity,
    StyleSheet,
    Image,
    ActivityIndicator,
    StatusBar,
    SafeAreaView,
} from 'react-native';

import { CameraView, useCameraPermissions } from 'expo-camera';
import * as ImagePicker from 'expo-image-picker';
import { Ionicons } from '@expo/vector-icons';

export default function TelaCamera({ navigation }) {

    const [permission, requestPermission] = useCameraPermissions();

    // Guarda a imagem escolhida ou tirada
    const [imagem, setImagem] = useState(null);

    // Evita toque duplo enquanto a foto é tirada
    const [tirandoFoto, setTirandoFoto] = useState(false);

    const cameraRef = useRef(null);

    // ============================
    // ABRIR GALERIA
    // ============================
    const abrirGaleria = useCallback(async () => {

        try {
            const resultado = await ImagePicker.launchImageLibraryAsync({
                mediaTypes: ['images'],
                allowsEditing: true,
                aspect: [4, 3],
                quality: 1,
            });

            if (!resultado.canceled) {
                setImagem(resultado.assets[0].uri);
            }
        } catch (erro) {
            console.warn('Não foi possível abrir a galeria:', erro);
        }

    }, []);

    // ============================
    // TIRAR FOTO
    // ============================
    const tirarFoto = useCallback(async () => {

        if (!cameraRef.current || tirandoFoto) {
            return;
        }

        try {
            setTirandoFoto(true);

            const foto = await cameraRef.current.takePictureAsync();

            setImagem(foto.uri);
        } catch (erro) {
            console.warn('Não foi possível tirar a foto:', erro);
        } finally {
            setTirandoFoto(false);
        }

    }, [tirandoFoto]);

    // ============================
    // NOVA FOTO
    // ============================
    const tirarOutraFoto = useCallback(() => {
        setImagem(null);
    }, []);

    // ============================
    // PERMISSÃO DA CÂMERA
    // ============================
    if (!permission) {
        return <View style={styles.container} />;
    }

    if (!permission.granted) {

        return (
            <SafeAreaView style={styles.containerPermissao}>

                <StatusBar barStyle="light-content" />

                <View style={styles.iconePermissao}>
                    <Ionicons name="camera-outline" size={48} color="#7CD37E" />
                </View>

                <Text style={styles.tituloPermissao}>
                    Precisamos da câmera
                </Text>

                <Text style={styles.texto}>
                    Para escanear o resíduo e identificar o material,
                    permita o acesso à câmera do aparelho.
                </Text>

                <TouchableOpacity
                    style={styles.botao}
                    onPress={requestPermission}
                    activeOpacity={0.85}
                >
                    <Text style={styles.textoBotao}>
                        Permitir câmera
                    </Text>
                </TouchableOpacity>

            </SafeAreaView>
        );
    }

    // ============================
    // TELA PRINCIPAL
    // ============================
    return (
        <View style={styles.container}>

            <StatusBar barStyle="light-content" />

            {/* =========================
                TOPO
            ========================== */}

            <SafeAreaView style={styles.topoSafe} pointerEvents="box-none">
                <View style={styles.topo}>

                    <TouchableOpacity
                        style={styles.botaoTopo}
                        onPress={() => navigation.goBack()}
                        activeOpacity={0.85}
                    >
                        <Ionicons name="close" size={24} color="#FFFFFF" />
                    </TouchableOpacity>

                    <Text style={styles.tituloTopo}>
                        {imagem ? 'Confirme a foto' : 'Aponte para o resíduo'}
                    </Text>

                    <View style={styles.botaoTopo} />

                </View>
            </SafeAreaView>

            {/* =========================
                SE NÃO TIVER IMAGEM
            ========================== */}

            {!imagem && (
                <View style={styles.molduraCamera}>
                    <CameraView
                        ref={cameraRef}
                        style={styles.camera}
                        facing="back"
                    />

                    <View style={styles.miraContainer} pointerEvents="none">
                        <View style={styles.mira} />
                        <Text style={styles.dicaTexto}>
                            Centralize o item na moldura
                        </Text>
                    </View>
                </View>
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

            <SafeAreaView style={styles.areaBotoesSafe}>

                {!imagem ? (

                    <View style={styles.barraInferior}>

                        {/* GALERIA */}
                        <TouchableOpacity
                            style={styles.botaoGaleria}
                            onPress={abrirGaleria}
                            activeOpacity={0.8}
                        >
                            <Ionicons name="images-outline" size={24} color="#FFFFFF" />
                            <Text style={styles.textoBotaoSecundario}>
                                Galeria
                            </Text>
                        </TouchableOpacity>

                        {/* CÂMERA */}
                        <TouchableOpacity
                            style={styles.botaoFoto}
                            onPress={tirarFoto}
                            activeOpacity={0.85}
                            disabled={tirandoFoto}
                        >
                            {tirandoFoto ? (
                                <ActivityIndicator color="#0A8443" />
                            ) : (
                                <View style={styles.circulo} />
                            )}
                        </TouchableOpacity>

                        {/* espaço vazio para manter o botão de foto centralizado */}
                        <View style={styles.espacoLateral} />

                    </View>

                ) : (

                    <View style={styles.barraConfirmacao}>

                        <TouchableOpacity
                            style={styles.botaoOutraFoto}
                            onPress={tirarOutraFoto}
                            activeOpacity={0.85}
                        >
                            <Ionicons name="camera-reverse-outline" size={20} color="#FFFFFF" />
                            <Text style={styles.textoBotaoSecundario}>
                                Outra foto
                            </Text>
                        </TouchableOpacity>

                        <TouchableOpacity
                            style={styles.botaoUsarFoto}
                            activeOpacity={0.85}
                        >
                            <Ionicons name="checkmark" size={20} color="#FFFFFF" />
                            <Text style={styles.textoBotao}>
                                Usar foto
                            </Text>
                        </TouchableOpacity>

                    </View>

                )}

            </SafeAreaView>

        </View>
    );
}


// ============================
// ESTILOS
// ============================

const styles = StyleSheet.create({

    container: {
        flex: 1,
        backgroundColor: '#0A0A0A',
    },


    /* ===== PERMISSÃO ===== */

    containerPermissao: {
        flex: 1,
        backgroundColor: '#0A0A0A',
        alignItems: 'center',
        justifyContent: 'center',
        paddingHorizontal: 32,
    },

    iconePermissao: {
        width: 84,
        height: 84,
        borderRadius: 42,
        backgroundColor: 'rgba(124, 211, 126, 0.12)',
        alignItems: 'center',
        justifyContent: 'center',
        marginBottom: 20,
    },

    tituloPermissao: {
        color: '#FFFFFF',
        fontSize: 20,
        fontWeight: '700',
        marginBottom: 10,
    },

    texto: {
        color: '#B5B5B5',
        textAlign: 'center',
        marginBottom: 28,
        fontSize: 14,
        lineHeight: 20,
    },

    botao: {
        backgroundColor: '#0A8443',
        paddingVertical: 15,
        paddingHorizontal: 36,
        borderRadius: 30,
    },

    textoBotao: {
        color: '#fff',
        textAlign: 'center',
        fontSize: 15,
        fontWeight: '700',
        marginLeft: 6,
    },


    /* ===== TOPO ===== */

    topoSafe: {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        zIndex: 2,
    },

    topo: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 16,
        paddingTop: 28,
    },

    botaoTopo: {
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(0,0,0,0.45)',
        alignItems: 'center',
        justifyContent: 'center',
    },

    tituloTopo: {
        color: '#FFFFFF',
        fontSize: 15,
        fontWeight: '600',
    },


    /* ===== CÂMERA ===== */

    molduraCamera: {
        flex: 1,
        marginTop: 90,
        marginHorizontal: 14,
        marginBottom: 150,
        borderRadius: 24,
        overflow: 'hidden',
        backgroundColor: '#000',
    },

    camera: {
        flex: 1,
    },

    miraContainer: {
        ...StyleSheet.absoluteFillObject,
        alignItems: 'center',
        justifyContent: 'center',
    },

    mira: {
        width: '72%',
        aspectRatio: 1,
        borderRadius: 20,
        borderWidth: 2,
        borderColor: 'rgba(255,255,255,0.75)',
        borderStyle: 'dashed',
    },

    dicaTexto: {
        color: 'rgba(255,255,255,0.85)',
        fontSize: 12,
        marginTop: 14,
        backgroundColor: 'rgba(0,0,0,0.4)',
        paddingHorizontal: 12,
        paddingVertical: 6,
        borderRadius: 14,
        overflow: 'hidden',
    },

    imagem: {
        flex: 1,
        width: '100%',
        resizeMode: 'contain',
    },


    /* ===== BOTÕES INFERIORES ===== */

    areaBotoesSafe: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
    },

    barraInferior: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 30,
        paddingBottom: 20,
        paddingTop: 10,
    },

    botaoGaleria: {
        width: 64,
        alignItems: 'center',
    },

    espacoLateral: {
        width: 64,
    },

    botaoFoto: {
        width: 76,
        height: 76,
        borderRadius: 38,
        backgroundColor: '#FFFFFF',
        justifyContent: 'center',
        alignItems: 'center',
        borderWidth: 4,
        borderColor: 'rgba(255,255,255,0.35)',
    },

    circulo: {
        width: 60,
        height: 60,
        borderRadius: 30,
        backgroundColor: '#0A8443',
    },

    textoBotaoSecundario: {
        color: '#FFFFFF',
        fontSize: 12,
        marginTop: 4,
        fontWeight: '600',
    },

    barraConfirmacao: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingHorizontal: 20,
        paddingBottom: 24,
        paddingTop: 10,
        gap: 12,
    },

    botaoOutraFoto: {
        flex: 1,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'rgba(255,255,255,0.15)',
        paddingVertical: 14,
        borderRadius: 30,
    },

    botaoUsarFoto: {
        flex: 1,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#0A8443',
        paddingVertical: 14,
        borderRadius: 30,
    },

});