import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  Image,
  Alert,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import axios from 'axios';

const API_Usuario = 'http://localhost:8000/api/usuario'; 


export default function CadastroScreen({ navigation }) {
  const [nome, setNome] = useState('');
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');


  const handleEnviar = () => {

  if (!nome || !email || !senha ) {
      Alert.alert('Atenção', 'Por favor, preencha todos os campos.');
      return;
    }

    setLoading(true);

     setLoading(true);

    const tarefaDados = {

      titulo: tituloEdit,

      descricao: descricaoEdit,

      status: statusEdit,

      prioridade: prioridadeEdit,

      data_vencimento: dataVEdit,

    };


    axios.put(
      `${API_Tarefa}/${tarefaSelecionada.id}`,
      tarefaDados
    )

      .then((response) => {

        Alert.alert(
          'Sucesso',
          'Tarefa alterada com sucesso!'
        );

        setEditar(false);

        setTarefaSelecionada(null);

        carregarTarefas();

      })

      .catch((err) => {

        console.error(
          "Erro na requisição PUT Axios:",
          err
        );

        if (err.response) {

          console.error(
            "Resposta da API:",
            err.response.data
          );

        }

        Alert.alert(
          'Erro',
          'Não foi possível alterar a tarefa.'
        );

      })

      .finally(() => {

        setLoadingAlteracao(false);

      });
  };


  const handleVoltar = () => {
   
    if (navigation) navigation.goBack();
    console.log('Voltar');
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      
      <LinearGradient
        colors={['#0f5c2e', '#3fa63f']}
        start={{ x: 0, y: 0 }}
        end={{ x: 1, y: 1 }}
        style={styles.header}
      >
       
        <View style={styles.logoPlaceholder}>
         <Image
            source={require('../assets/logo.png')}
            style={styles.logoImage}
          />
        </View>
      </LinearGradient>

     
      <SafeAreaView style={styles.cardWrapper}>
        <KeyboardAvoidingView
          behavior={Platform.OS === 'ios' ? 'padding' : undefined}
          style={{ flex: 1 }}
        >
          <ScrollView
            contentContainerStyle={styles.card}
            keyboardShouldPersistTaps="handled"
          >
            <Text style={styles.title}>Edite seu Perfil</Text>

            
          <TextInput
              style={styles.input}
              placeholder="Titulo da Tarefa"
              value={tituloEdit}
              onChangeText={setTituloEdit}
            />


            <TextInput
              style={styles.input}
              placeholder="Descrição"
              value={descricaoEdit}
              onChangeText={setDescricaoEdit}
            />


            <TextInput
              style={styles.input}
              placeholder="Status: em_andamento"
              value={statusEdit}
              onChangeText={setStatusEdit}
            />


            <TextInput
              style={styles.input}
              placeholder="Prioridade"
              value={prioridadeEdit}
              onChangeText={setPrioridadeEdit}
            />


            <TextInput
              style={styles.input}
              placeholder="Data Final: AAAA-MM-DD"
              value={dataVEdit}
              onChangeText={setDataVEdit}
            />


            <TouchableOpacity
              style={styles.button}
              onPress={atualizarTarefa}
              disabled={loadingAlteracao}
            >

            <TouchableOpacity
              style={styles.backButton}
              onPress={handleVoltar}
              activeOpacity={0.85}
            >
              <Ionicons name="chevron-back" size={20} color={GREEN_DARK} />
            </TouchableOpacity>
          </ScrollView>
        </KeyboardAvoidingView>
      </SafeAreaView>
    </View>
  );
}

const GREEN_DARK = '#0f5c2e';
const GREEN_MAIN = '#3fa63f';
const GREEN_LIGHT = '#c9e8bd';
const BG_CREAM = '#f5f4ea';
const BORDER_BLUE = '#2b9fd6';
const TAM_IMG = 90;
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: GREEN_DARK,
  },
  header: {
    height: 160,
    alignItems: 'center',
    justifyContent: 'center',
  },
  logoImage: {
    width: TAM_IMG,
    height: TAM_IMG,
    resizeMode: 'contain',
  },
  cardWrapper: {
    flex: 1,
    marginTop: -30, 
  },
  card: {
    flexGrow: 1,
    backgroundColor: BG_CREAM,
    borderTopLeftRadius: 40,
    borderTopRightRadius: 40,
    borderBottomWidth: 0,
    paddingHorizontal: 28,
    paddingTop: 32,
    paddingBottom: 40,
    alignItems: 'center',
  },
  title: {
    fontSize: 24,
    fontWeight: '700',
    color: GREEN_DARK,
    letterSpacing: 1,
    marginBottom: 28,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    width: '100%',
    backgroundColor: '#ffffff',
    borderRadius: 30,
    paddingHorizontal: 18,
    height: 52,
    marginBottom: 18,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    fontSize: 15,
    color: '#333333',
  },
  criarButton: {
    width: '60%',
    height: 40,
    backgroundColor: GREEN_MAIN,
    borderRadius: 30,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 6,
    shadowColor: GREEN_MAIN,
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 3,
  },
  criarButtonText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '700',
    letterSpacing: 1,
  },
  backButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: GREEN_LIGHT,
    alignItems: 'center',
    justifyContent: 'center',
    alignSelf: 'flex-start',
    marginTop:10,
  },
});
