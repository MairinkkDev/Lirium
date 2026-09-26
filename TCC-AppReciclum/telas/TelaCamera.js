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
    Alert,
} from 'react-native';

import { CameraView, useCameraPermissions } from 'expo-camera';

import * as ImagePicker from 'expo-image-picker';

import { fetch as expoFetch } from 'expo/fetch';

import { File } from 'expo-file-system';

import { Ionicons } from '@expo/vector-icons';


// ============================================================
// ENDEREÇO DO FASTAPI
// ============================================================

const API_URL = 'http://SEU_IP_LOCAL:8000';


export default function TelaCamera({ navigation }) {

    const [permission, requestPermission] =
        useCameraPermissions();

    // Guarda a imagem tirada/escolhida
    const [imagem, setImagem] = useState(null);

    // Evita tirar duas fotos ao mesmo tempo
    const [tirandoFoto, setTirandoFoto] =
        useState(false);

    // Indica que o YOLO está analisando
    const [analisando, setAnalisando] =
        useState(false);

    const cameraRef = useRef(null);


    // ============================================================
    // ENVIAR IMAGEM PARA O FASTAPI / YOLO
    // ============================================================

    const enviarImagem = useCallback(async (uri) => {

        try {

            setAnalisando(true);


            // Cria o arquivo a partir da URI
            const arquivo = new File(uri);


            console.log('Arquivo:', arquivo.name);

            console.log(
                'Tamanho:',
                arquivo.size,
                'bytes'
            );

            console.log(
                'Tipo:',
                arquivo.type
            );


            // Cria o formulário
            const formData = new FormData();


            // Coloca a imagem dentro do formulário
            formData.append('file', arquivo);


            console.log(
                'Enviando imagem para a API...'
            );


            // Envia para o FastAPI
            const resposta = await expoFetch(
                `${API_URL}/detectar`,
                {
                    method: 'POST',
                    body: formData,
                }
            );


            console.log(
                'Status da API:',
                resposta.status
            );


            // Transforma a resposta em JSON
            const resultado =
                await resposta.json();


            console.log(
                'Resposta da API:',
                resultado
            );


            // ====================================================
            // MOSTRAR RESULTADO
            // ====================================================

            if (
                resultado.objetos &&
                resultado.objetos.length > 0
            ) {

                // Pega somente os nomes
                const nomes =
                    resultado.objetos
                        .map(objeto => objeto.nome)
                        .join('\n');


                Alert.alert(
                    'Resultado',
                    `Objeto detectado:\n\n${nomes}`,
                    [
                        {
                            text: 'OK',
                            onPress: () => navigation.goBack(),
                        },
                    ]
                );

            } else {

                Alert.alert(
                    'Resultado',
                    'Nenhum objeto foi detectado.',
                    [
                        {
                            text: 'OK',
                            onPress: () => navigation.goBack(),
                        },
                    ]
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


        } finally {

            setAnalisando(false);

        }

    }, []);


    // ============================================================
    // ABRIR GALERIA
    // ============================================================

    const abrirGaleria = useCallback(async () => {

        try {

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


                // Mostra a imagem para confirmação
                setImagem(uri);

            }


        } catch (erro) {

            console.warn(
                'Não foi possível abrir a galeria:',
                erro
            );

        }

    }, []);


    // ============================================================
    // TIRAR FOTO
    // ============================================================

    const tirarFoto = useCallback(async () => {

        if (
            !cameraRef.current ||
            tirandoFoto
        ) {

            return;

        }


        try {

            setTirandoFoto(true);


            const foto =
                await cameraRef.current
                    .takePictureAsync();


            // Mostra a foto para confirmação
            setImagem(foto.uri);


        } catch (erro) {

            console.warn(
                'Não foi possível tirar a foto:',
                erro
            );

        } finally {

            setTirandoFoto(false);

        }

    }, [tirandoFoto]);


    // ============================================================
    // NOVA FOTO
    // ============================================================

    const tirarOutraFoto = useCallback(() => {

        setImagem(null);

    }, []);


    // ============================================================
    // USAR FOTO
    // ============================================================

    const usarFoto = useCallback(async () => {

        if (!imagem || analisando) {

            return;

        }


        await enviarImagem(imagem);

    }, [
        imagem,
        analisando,
        enviarImagem,
    ]);


    // ============================================================
    // PERMISSÃO DA CÂMERA
    // ============================================================

    if (!permission) {

        return (
            <View style={styles.container} />
        );

    }


    if (!permission.granted) {

        return (

            <SafeAreaView
                style={styles.containerPermissao}
            >

                <StatusBar
                    barStyle="light-content"
                />


                <View style={styles.iconePermissao}>

                    <Ionicons
                        name="camera-outline"
                        size={48}
                        color="#7CD37E"
                    />

                </View>


                <Text
                    style={styles.tituloPermissao}
                >
                    Precisamos da câmera
                </Text>


                <Text style={styles.texto}>

                    Para escanear o resíduo e
                    identificar o material,
                    permita o acesso à câmera
                    do aparelho.

                </Text>


                <TouchableOpacity
                    style={styles.botao}
                    onPress={requestPermission}
                    activeOpacity={0.85}
                >

                    <Text
                        style={styles.textoBotao}
                    >
                        Permitir câmera
                    </Text>

                </TouchableOpacity>

            </SafeAreaView>

        );

    }


    // ============================================================
    // TELA PRINCIPAL
    // ============================================================

    return (

        <View style={styles.container}>


            <StatusBar
                barStyle="light-content"
            />


            {/* =================================================
                TOPO
            ================================================== */}

            <SafeAreaView
                style={styles.topoSafe}
                pointerEvents="box-none"
            >

                <View style={styles.topo}>


                    <TouchableOpacity
                        style={styles.botaoTopo}
                        onPress={() =>
                            navigation.goBack()
                        }
                        activeOpacity={0.85}
                    >

                        <Ionicons
                            name="close"
                            size={24}
                            color="#FFFFFF"
                        />

                    </TouchableOpacity>


                    <Text
                        style={styles.tituloTopo}
                    >

                        {imagem
                            ? 'Confirme a foto'
                            : 'Aponte para o resíduo'
                        }

                    </Text>


                    <View
                        style={styles.botaoTopo}
                    />

                </View>

            </SafeAreaView>


            {/* =================================================
                CÂMERA
            ================================================== */}

            {!imagem && (

                <View
                    style={styles.molduraCamera}
                >

                    <CameraView
                        ref={cameraRef}
                        style={styles.camera}
                        facing="back"
                    />


                    <View
                        style={styles.miraContainer}
                        pointerEvents="none"
                    >

                        <View
                            style={styles.mira}
                        />


                        <Text
                            style={styles.dicaTexto}
                        >

                            Centralize o item
                            na moldura

                        </Text>

                    </View>

                </View>

            )}


            {/* =================================================
                IMAGEM
            ================================================== */}

            {imagem && (

                <Image
                    source={{ uri: imagem }}
                    style={styles.imagem}
                />

            )}


            {/* =================================================
                BOTÕES
            ================================================== */}

            <SafeAreaView
                style={styles.areaBotoesSafe}
            >


                {!imagem ? (

                    <View
                        style={styles.barraInferior}
                    >


                        {/* GALERIA */}

                        <TouchableOpacity
                            style={styles.botaoGaleria}
                            onPress={abrirGaleria}
                            activeOpacity={0.8}
                        >

                            <Ionicons
                                name="images-outline"
                                size={24}
                                color="#FFFFFF"
                            />


                            <Text
                                style={
                                    styles.textoBotaoSecundario
                                }
                            >

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

                                <ActivityIndicator
                                    color="#0A8443"
                                />

                            ) : (

                                <View
                                    style={styles.circulo}
                                />

                            )}

                        </TouchableOpacity>


                        <View
                            style={styles.espacoLateral}
                        />

                    </View>


                ) : (


                    <View
                        style={styles.barraConfirmacao}
                    >


                        {/* OUTRA FOTO */}

                        <TouchableOpacity
                            style={styles.botaoOutraFoto}
                            onPress={tirarOutraFoto}
                            activeOpacity={0.85}
                            disabled={analisando}
                        >

                            <Ionicons
                                name="camera-reverse-outline"
                                size={20}
                                color="#FFFFFF"
                            />


                            <Text
                                style={
                                    styles.textoBotaoSecundario
                                }
                            >

                                Outra foto

                            </Text>

                        </TouchableOpacity>


                        {/* USAR FOTO */}

                        <TouchableOpacity
                            style={styles.botaoUsarFoto}
                            onPress={usarFoto}
                            activeOpacity={0.85}
                            disabled={analisando}
                        >

                            {analisando ? (

                                <ActivityIndicator
                                    color="#FFFFFF"
                                />

                            ) : (

                                <>

                                    <Ionicons
                                        name="checkmark"
                                        size={20}
                                        color="#FFFFFF"
                                    />


                                    <Text
                                        style={
                                            styles.textoBotao
                                        }
                                    >

                                        Usar foto

                                    </Text>

                                </>

                            )}

                        </TouchableOpacity>


                    </View>

                )}

            </SafeAreaView>

        </View>

    );

}


// ============================================================
// ESTILOS
// ============================================================

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
        backgroundColor:
            'rgba(124, 211, 126, 0.12)',
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
        backgroundColor:
            'rgba(0,0,0,0.45)',
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
        borderColor:
            'rgba(255,255,255,0.75)',
        borderStyle: 'dashed',
    },

    dicaTexto: {
        color: 'rgba(255,255,255,0.85)',
        fontSize: 12,
        marginTop: 14,
        backgroundColor:
            'rgba(0,0,0,0.4)',
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


    /* ===== BOTÕES ===== */

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
        borderColor:
            'rgba(255,255,255,0.35)',
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
        backgroundColor:
            'rgba(255,255,255,0.15)',
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
