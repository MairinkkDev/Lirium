import React, { useRef, useState } from 'react';

import {
    View,
    Text,
    TouchableOpacity,
    StyleSheet,
    Image,
    Alert
} from 'react-native';

import { CameraView, useCameraPermissions } from 'expo-camera';
import * as ImagePicker from 'expo-image-picker';

import { fetch as expoFetch } from 'expo/fetch';
import { File } from 'expo-file-system';

const API_URL = 'http://192.168.1.79:8000';

export default function TelaCamera({ navigation }) {

    const [permission, requestPermission] = useCameraPermissions();

    const [imagem, setImagem] = useState(null);

    const cameraRef = useRef(null);

    async function enviarImagem(uri) {

        try {

            const arquivo = new File(uri);

            console.log('Arquivo:', arquivo.name);
            console.log('Tamanho:', arquivo.size, 'bytes');
            console.log('Tipo:', arquivo.type);


            const formData = new FormData();

            formData.append('file', arquivo);


            console.log('Enviando imagem para a API...');


            const resposta = await expoFetch(
                `${API_URL}/detectar`,
                {
                    method: 'POST',
                    body: formData,
                }
            );


            console.log('Status da API:', resposta.status);


            const resultado = await resposta.json();


            console.log('Resposta da API:', resultado);

            if (
                resultado.objetos &&
                resultado.objetos.length > 0
            ) {

                const nomes = resultado.objetos
                    .map(objeto => objeto.nome)
                    .join('\n');


                Alert.alert(
                    'Resultado',
                    `Objeto detectado:\n\n${nomes}`
                );


            } else {

                Alert.alert(
                    'Resultado',
                    'Nenhum objeto foi detectado.'
                );

            }


        } catch (erro) {

            console.log(
                'Erro ao enviar imagem:',
                erro
            );


            Alert.alert(
                'Erro',
                'Não foi possível enviar a imagem para a API.'
            );

        }

    }

    async function abrirGaleria() {

        const resultado =
            await ImagePicker.launchImageLibraryAsync({

                mediaTypes: ['images'],

                allowsEditing: true,

                aspect: [4, 3],

                quality: 1,

            });


        if (!resultado.canceled) {

            const uri =
                resultado.assets[0].uri;


            console.log(
                'Imagem selecionada:',
                uri
            );


            // Mostra a imagem na tela
            setImagem(uri);


            // Envia para o YOLO
            await enviarImagem(uri);

        }

    }

    async function tirarFoto() {

        if (cameraRef.current) {

            const foto =
                await cameraRef.current.takePictureAsync();


            console.log(
                'Imagem tirada:',
                foto.uri
            );


            // Mostra a imagem na tela
            setImagem(foto.uri);


            // Envia para o YOLO
            await enviarImagem(foto.uri);

        }

    }

    if (!permission) {

        return <View />;

    }


    if (!permission.granted) {

        return (

            <View style={styles.container}>

                <Text style={styles.texto}>

                    Precisamos de permissão
                    para usar a câmera.

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

    return (

        <View style={styles.container}>

            {!imagem && (

                <CameraView
                    ref={cameraRef}
                    style={styles.camera}
                    facing="back"
                />

            )}

            {imagem && (

                <Image
                    source={{ uri: imagem }}
                    style={styles.imagem}
                />

            )}

            <View style={styles.areaBotoes}>


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


                {/* BOTÃO DA CÂMERA */}

                {!imagem && (

                    <TouchableOpacity
                        style={styles.botaoFoto}
                        onPress={tirarFoto}
                    >

                        <View style={styles.circulo} />

                    </TouchableOpacity>

                )}

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
