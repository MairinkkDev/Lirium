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

const API_URL = 'http://SEU_IPI_LOCAL:8000';

const materiaisCompativeis = {
    papelao: 'papelao',
    garrafa_plastico: 'pet',
    garrafa_vidro: 'vidro',
    copo_vidro: 'vidro',
    lata_metal: 'lata',
    lata_alimento: 'lata',
};

export default function TelaCamera({ navigation }) {
    const [permission, requestPermission] = useCameraPermissions();
    const [imagem, setImagem] = useState(null);
    const [tirandoFoto, setTirandoFoto] = useState(false);
    const [analisando, setAnalisando] = useState(false);

    const cameraRef = useRef(null);

    // Envia imagem para a fastapi para identificar o objeto
    const enviarImagem = useCallback(async (uri) => {
        try {
            setAnalisando(true);

            const arquivo = new File(uri);
            const formData = new FormData();

            formData.append('file', arquivo);

            const resposta = await expoFetch(`${API_URL}/detectar`, {
                method: 'POST',
                body: formData,
            });

            const resultado = await resposta.json();

            if (!resposta.ok) {
                throw new Error(
                    resultado.detail || `Erro HTTP ${resposta.status}`
                );
            }

            console.log('Resposta da API:', resultado);

            // Aceita respostas com "objetos" ou "deteccoes".
            const objetos = resultado.objetos || resultado.deteccoes || [];

            // Procura o primeiro objeto com uma categoria compatível.
            const objetoCompativel = objetos.find((objeto) => {
                const nomeOriginal = objeto.nome ?? objeto.classe;

                const nome = String(nomeOriginal || '')
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '_');

                return Boolean(materiaisCompativeis[nome]);
            });

            if (objetoCompativel) {
                const nomeOriginal =
                    objetoCompativel.nome ?? objetoCompativel.classe;

                const nome = String(nomeOriginal)
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '_');

                const materialId = materiaisCompativeis[nome];

                // Formata o nome para exibição no alerta.
                const nomeExibicao = String(nomeOriginal)
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, (letra) => letra.toUpperCase());

                console.log('Objeto reconhecido:', nome);
                console.log('Categoria de reciclagem:', materialId);

                // Mostra o objeto identificado antes de redirecionar para a tela com informações
                Alert.alert(
                    'Objeto identificado!',
                    `Identificamos: ${nomeExibicao}.\n\nVamos mostrar as informações de reciclagem desse material.`,
                    [
                        {
                            text: 'Continuar',
                            onPress: () => {
                                navigation.navigate('Reciclagem', {
                                    materialId: materialId,
                                });
                            },
                        },
                    ],
                    { cancelable: false }
                );
            } else {
                Alert.alert(
                    'Resultado da análise',
                    objetos.length > 0
                        ? 'A IA reconheceu um objeto, mas ainda não temos uma categoria de reciclagem correspondente.'
                        : 'Nenhum objeto compatível foi detectado.',
                    [
                        {
                            text: 'Tentar novamente',
                            onPress: () => setImagem(null),
                        },
                        {
                            text: 'Voltar',
                            onPress: () => navigation.goBack(),
                        },
                    ]
                );
            }
        } catch (erro) {
            console.log('Erro ao enviar imagem:', erro);

            Alert.alert(
                'Erro',
                'Não foi possível enviar a imagem para a API. Verifique a conexão e o endereço do servidor.'
            );
        } finally {
            setAnalisando(false);
        }
    }, [navigation]);

    // Abre a galeria
    const abrirGaleria = useCallback(async () => {
        try {
            const resultado =
                await ImagePicker.launchImageLibraryAsync({
                    mediaTypes: ['images'],
                    allowsEditing: true,
                    aspect: [4, 3],
                    quality: 1,
                });

            if (
                !resultado.canceled &&
                resultado.assets?.length > 0
            ) {
                setImagem(resultado.assets[0].uri);
            }
        } catch (erro) {
            console.warn('Erro ao abrir a galeria:', erro);

            Alert.alert(
                'Erro',
                'Não foi possível abrir a galeria.'
            );
        }
    }, []);

    // Tirar foto
    const tirarFoto = useCallback(async () => {
        if (!cameraRef.current || tirandoFoto) {
            return;
        }

        try {
            setTirandoFoto(true);

            const foto = await cameraRef.current.takePictureAsync();

            if (foto?.uri) {
                setImagem(foto.uri);
            }
        } catch (erro) {
            console.warn('Erro ao tirar foto:', erro);

            Alert.alert(
                'Erro',
                'Não foi possível tirar a foto.'
            );
        } finally {
            setTirandoFoto(false);
        }
    }, [tirandoFoto]);

    // Tirar outra foto
    const tirarOutraFoto = useCallback(() => {
        setImagem(null);
    }, []);

    // Confirmação para o envio
    const usarFoto = useCallback(async () => {
        if (!imagem || analisando) {
            return;
        }

        await enviarImagem(imagem);
    }, [imagem, analisando, enviarImagem]);

    // carregamento da permissão
    if (!permission) {
        return <View style={styles.container} />;
    }

    // Pedir premissão da câmera
    if (!permission.granted) {
        return (
            <SafeAreaView style={styles.containerPermissao}>
                <StatusBar barStyle="light-content" />

                <View style={styles.iconePermissao}>
                    <Ionicons
                        name="camera-outline"
                        size={48}
                        color="#7CD37E"
                    />
                </View>

                <Text style={styles.tituloPermissao}>
                    Precisamos da câmera
                </Text>

                <Text style={styles.textoPermissao}>
                    Para escanear o resíduo e identificar o material,
                    permita o acesso à câmera do aparelho.
                </Text>

                <TouchableOpacity
                    style={styles.botaoPermissao}
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

    // Tela principal
    return (
        <SafeAreaView style={styles.container}>
            <StatusBar barStyle="light-content" />

            {/* CABEÇALHO */}
            <View style={styles.topo}>
                <TouchableOpacity
                    style={styles.botaoTopo}
                    onPress={() => navigation.goBack()}
                    activeOpacity={0.85}
                >
                    <Ionicons
                        name="close"
                        size={24}
                        color="#FFFFFF"
                    />
                </TouchableOpacity>

                <Text style={styles.tituloTopo}>
                    {imagem
                        ? 'Confirme a foto'
                        : 'Aponte para o resíduo'}
                </Text>

                <View style={styles.botaoTopo} />
            </View>

            {/* ÁREA CENTRAL DA CÂMERA OU DA FOTO */}
            <View style={styles.areaCentral}>
                {!imagem ? (
                    <View style={styles.cameraContainer}>
                        <CameraView
                            ref={cameraRef}
                            style={StyleSheet.absoluteFill}
                            facing="back"
                        />

                        {/* MIRA CENTRALIZADA */}
                        <View
                            style={styles.miraContainer}
                            pointerEvents="none"
                        >
                            <View style={styles.mira} />

                            <Text style={styles.dicaTexto}>
                                Centralize o item na mira
                            </Text>
                        </View>
                    </View>
                ) : (
                    <View style={styles.molduraImagem}>
                        <Image
                            source={{ uri: imagem }}
                            style={styles.imagem}
                            resizeMode="contain"
                        />
                    </View>
                )}
            </View>

            {/* BOTÕES INFERIORES */}
            <View style={styles.areaBotoes}>
                {!imagem ? (
                    <View style={styles.barraInferior}>
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

                            <Text style={styles.textoBotaoSecundario}>
                                Galeria
                            </Text>
                        </TouchableOpacity>

                        <TouchableOpacity
                            style={styles.botaoFoto}
                            onPress={tirarFoto}
                            activeOpacity={0.85}
                            disabled={tirandoFoto || analisando}
                        >
                            {tirandoFoto ? (
                                <ActivityIndicator color="#0A8443" />
                            ) : (
                                <View style={styles.circulo} />
                            )}
                        </TouchableOpacity>

                        <View style={styles.espacoLateral} />
                    </View>
                ) : (
                    <View style={styles.barraConfirmacao}>
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

                            <Text style={styles.textoBotaoSecundario}>
                                Outra foto
                            </Text>
                        </TouchableOpacity>

                        <TouchableOpacity
                            style={[
                                styles.botaoUsarFoto,
                                analisando && styles.botaoDesabilitado,
                            ]}
                            onPress={usarFoto}
                            activeOpacity={0.85}
                            disabled={analisando}
                        >
                            {analisando ? (
                                <>
                                    <ActivityIndicator color="#FFFFFF" />
                                    <Text style={styles.textoBotao}>
                                        Analisando...
                                    </Text>
                                </>
                            ) : (
                                <>
                                    <Ionicons
                                        name="checkmark"
                                        size={20}
                                        color="#FFFFFF"
                                    />

                                    <Text style={styles.textoBotao}>
                                        Usar foto
                                    </Text>
                                </>
                            )}
                        </TouchableOpacity>
                    </View>
                )}
            </View>
        </SafeAreaView>
    );
}

// ESTILOS
const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#0A0A0A',
    },

    // PERMISSÕES
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

    textoPermissao: {
        color: '#B5B5B5',
        textAlign: 'center',
        marginBottom: 28,
        fontSize: 14,
        lineHeight: 20,
    },

    botaoPermissao: {
        backgroundColor: '#0A8443',
        paddingVertical: 15,
        paddingHorizontal: 36,
        borderRadius: 30,
    },

    textoBotao: {
        color: '#FFFFFF',
        textAlign: 'center',
        fontSize: 15,
        fontWeight: '700',
        marginLeft: 6,
    },

    // CABEÇALHO
    topo: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 16,
        paddingVertical: 10,
    },

    botaoTopo: {
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(255,255,255,0.10)',
        alignItems: 'center',
        justifyContent: 'center',
    },

    tituloTopo: {
        flex: 1,
        color: '#FFFFFF',
        fontSize: 15,
        fontWeight: '600',
        textAlign: 'center',
        marginHorizontal: 8,
    },

    // ÁREA CENTRAL
    areaCentral: {
        flex: 1,
        minHeight: 0,
        width: '100%',
    },

    cameraContainer: {
        flex: 1,
        width: '100%',
        minHeight: 0,
        overflow: 'hidden',
        backgroundColor: '#000000',
    },

    miraContainer: {
        ...StyleSheet.absoluteFillObject,
        alignItems: 'center',
        justifyContent: 'center',
    },

    mira: {
        width: '80%',
        top: '50%',
        aspectRatio: 1,
        borderRadius: 20,
        borderWidth: 2,
        borderColor: 'rgba(255,255,255,0.85)',
        borderStyle: 'dashed',
    },

    dicaTexto: {
        position: 'absolute',
        top: '170%',
        color: '#FFFFFF',
        fontSize: 12,
        backgroundColor: 'rgba(0,0,0,0.55)',
        paddingHorizontal: 12,
        paddingVertical: 7,
        borderRadius: 14,
        overflow: 'hidden',
        textAlign: 'center',
    },

    molduraImagem: {
        flex: 1,
        minHeight: 0,
        overflow: 'hidden',
        backgroundColor: '#000000',
        alignItems: 'center',
        justifyContent: 'center',
    },

    imagem: {
        width: '100%',
        height: '100%',
    },

    // BOTÕES INFERIORES
    areaBotoes: {
        paddingHorizontal: 20,
        paddingTop: 8,
        paddingBottom: 10,
    },

    barraInferior: {
        minHeight: 82,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 10,
    },

    botaoGaleria: {
        width: 64,
        alignItems: 'center',
        justifyContent: 'center',
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
        minHeight: 82,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
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
        gap: 4,
    },

    botaoUsarFoto: {
        flex: 1,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#0A8443',
        paddingVertical: 14,
        borderRadius: 30,
        gap: 4,
    },

    botaoDesabilitado: {
        opacity: 0.7,
    },
});