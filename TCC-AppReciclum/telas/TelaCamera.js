import { useState } from 'react';

import { StatusBar } from 'expo-status-bar';

import {
  StyleSheet,
  Text,
  View,
  TouchableOpacity,
  Alert,
} from 'react-native';

import { fetch as expoFetch } from 'expo/fetch';
import { File } from 'expo-file-system';

import * as ImagePicker from 'expo-image-picker';


const API_URL = 'http://SEU_IP_LOCAL:8000';


export default function App() {

  const [carregando, setCarregando] = useState(false);

  async function enviarImagem(uri) {

    try {

      setCarregando(true);


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


      // ========================================================
      // MOSTRA O RESULTADO
      // ========================================================

      if (
        resultado.objetos &&
        resultado.objetos.length > 0
      ) {

        const nomes = resultado.objetos
          .map(objeto => objeto.nome)
          .join('\n');


        Alert.alert(
          'Resultado da análise',
          `Objeto(s) detectado(s):\n\n${nomes}`
        );

      } else {

        Alert.alert(
          'Resultado da análise',
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


    } finally {

      setCarregando(false);

    }

  }

  function escanearImagem() {

    Alert.alert(
      'Escanear imagem',
      'Escolha uma opção',

      [

        {
          text: 'Câmera',
          onPress: abrirCamera,
        },

        {
          text: 'Galeria',
          onPress: abrirGaleria,
        },

        {
          text: 'Cancelar',
          style: 'cancel',
        },

      ]

    );

  }

  async function abrirCamera() {

    const permissao =
      await ImagePicker.requestCameraPermissionsAsync();


    if (!permissao.granted) {

      Alert.alert(
        'Permissão necessária',
        'Precisamos da permissão para acessar a câmera.'
      );

      return;

    }


    const resultadoCamera =
      await ImagePicker.launchCameraAsync({

        mediaTypes: ['images'],

        quality: 1,

      });


    if (!resultadoCamera.canceled) {

      const uri =
        resultadoCamera.assets[0].uri;


      console.log(
        'Imagem tirada:',
        uri
      );


      // Envia imediatamente para o YOLO
      await enviarImagem(uri);

    }

  }

  async function abrirGaleria() {

    const resultadoGaleria =
      await ImagePicker.launchImageLibraryAsync({

        mediaTypes: ['images'],

        quality: 1,

      });


    if (!resultadoGaleria.canceled) {

      const uri =
        resultadoGaleria.assets[0].uri;


      console.log(
        'Imagem selecionada:',
        uri
      );


      // Envia imediatamente para o YOLO
      await enviarImagem(uri);

    }

  }

  return (

    <View style={styles.container}>

      <Text style={styles.titulo}>
        Scanner de Reciclagem
      </Text>


      <TouchableOpacity
        style={styles.botao}
        onPress={escanearImagem}
        disabled={carregando}
      >

        <Text style={styles.textoBotao}>

          {carregando
            ? 'Analisando...'
            : 'Escanear imagem'
          }

        </Text>

      </TouchableOpacity>


      <StatusBar style="auto" />

    </View>

  );

}

const styles = StyleSheet.create({

  container: {

    flex: 1,

    backgroundColor: '#fff',

    padding: 20,

    paddingTop: 60,

  },


  titulo: {

    fontSize: 28,

    fontWeight: 'bold',

    marginBottom: 20,

  },


  botao: {

    paddingVertical: 20,

    paddingHorizontal: 50,

    borderRadius: 10,

    backgroundColor: '#000',

    alignItems: 'center',

  },


  textoBotao: {

    color: '#fff',

    fontSize: 18,

    fontWeight: 'bold',

  },

});


