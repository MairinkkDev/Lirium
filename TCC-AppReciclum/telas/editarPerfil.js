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
  Modal,
  ActivityIndicator,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import axios from 'axios';
import { useUsuario, API_URL } from '../context/UsuarioContext';

export default function EditarPerfilScreen({ navigation }) {
  const { usuario, setUsuario } = useUsuario();

  const [nome, setNome] = useState(usuario?.nomeUsuario ?? '');
  const [email, setEmail] = useState(usuario?.emailUsuario ?? '');
  const [senha, setSenha] = useState('');

  const [editando, setEditando] = useState(false);     
  const [confirmando, setConfirmando] = useState(false); 
  const [loading, setLoading] = useState(false);
  const [mensagem, setMensagem] = useState(null);     


  const handleAlterar = () => {
    setMensagem(null);
    setEditando(true);
  };


  const handleCancelar = () => {
    setNome(usuario?.nomeUsuario ?? '');
    setEmail(usuario?.emailUsuario ?? '');
    setSenha('');
    setMensagem(null);
    setEditando(false);
  };

  const handleSalvar = () => {
    if (!nome || !email) {
      setMensagem({ texto: 'Preencha o nome e o e-mail.', ok: false });
      return;
    }
    setMensagem(null);
    setConfirmando(true);
  };

  // botão CONFIRMAR do modal: envia para a API
  const confirmarSalvar = () => {
    setConfirmando(false);
    setLoading(true);

    const dados = {
      nomeUsuario: nome,
      emailUsuario: email,
    };

    // a senha só é enviada se a pessoa digitou uma nova
    if (senha) {
      dados.senhaUsuario = senha;
    }


    axios
      .put(`${API_URL}/me`, dados)
      .then((response) => {
        setUsuario(response.data); 
        setSenha('');
        setEditando(false);
        setMensagem({ texto: 'Perfil atualizado com sucesso.', ok: true });
      })
      .catch((err) => {
        console.error('Erro:', err.message);
        console.error('Status:', err.response?.status);
        console.error('Resposta:', JSON.stringify(err.response?.data, null, 2));

        if (err.response?.status === 401) {
          setMensagem({ texto: 'Sessão expirada. Faça login novamente.', ok: false });
        } else if (err.response?.status === 422) {
          const erros = err.response.data?.errors;
          const primeira = erros ? Object.values(erros)[0][0] : null;
          setMensagem({ texto: primeira || 'Verifique os dados informados.', ok: false });
        } else if (err.response) {
          setMensagem({ texto: `Erro do servidor (${err.response.status}).`, ok: false });
        } else if (err.code === 'ERR_NETWORK') {
          setMensagem({ texto: 'Sem conexão com o servidor.', ok: false });
        } else {
          // erro dentro do app (não veio da API): mostra o motivo real
          setMensagem({ texto: `Erro inesperado: ${err.message}`, ok: false });
        }
      })
      .finally(() => {
        setLoading(false)
      });
  };

  const handleVoltar = () => {
    if (navigation) navigation.goBack();
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
        <View>
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
            <Text style={styles.title}>EDITAR PERFIL</Text>

            <View style={[styles.inputContainer, !editando && styles.inputBloqueado]}>
              <Ionicons
                name="person-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder="Nome:"
                placeholderTextColor="#8a8a8a"
                value={nome}
                onChangeText={setNome}
                editable={editando}
              />
            </View>

            <View style={[styles.inputContainer, !editando && styles.inputBloqueado]}>
              <Ionicons
                name="mail-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder="Email:"
                placeholderTextColor="#8a8a8a"
                value={email}
                onChangeText={setEmail}
                autoCapitalize="none"
                keyboardType="email-address"
                editable={editando}
              />
            </View>

            <View style={[styles.inputContainer, !editando && styles.inputBloqueado]}>
              <Ionicons
                name="lock-closed-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder={editando ? 'Nova senha (opcional):' : 'Senha: ••••••••'}
                placeholderTextColor="#8a8a8a"
                value={senha}
                onChangeText={setSenha}
                secureTextEntry
                editable={editando}
              />
            </View>

            {mensagem ? (
              <Text style={[styles.mensagem, mensagem.ok ? styles.mensagemOk : styles.mensagemErro]}>
                {mensagem.texto}
              </Text>
            ) : null}

            {!editando ? (
              <TouchableOpacity
                style={styles.criarButton}
                onPress={handleAlterar}
                activeOpacity={0.85}
              >
                <Text style={styles.criarButtonText}>ALTERAR</Text>
              </TouchableOpacity>
            ) : (
              <>
                <TouchableOpacity
                  style={styles.criarButton}
                  onPress={handleSalvar}
                  activeOpacity={0.85}
                  disabled={loading}
                >
                  {loading ? (
                    <ActivityIndicator color="#ffffff" />
                  ) : (
                    <Text style={styles.criarButtonText}>SALVAR</Text>
                  )}
                </TouchableOpacity>

                <TouchableOpacity
                  style={styles.cancelarButton}
                  onPress={handleCancelar}
                  activeOpacity={0.85}
                  disabled={loading}
                >
                  <Text style={styles.cancelarButtonText}>CANCELAR</Text>
                </TouchableOpacity>
              </>
            )}

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

      {/* Modal de confirmação */}
      <Modal
        visible={confirmando}
        transparent
        animationType="fade"
        onRequestClose={() => setConfirmando(false)}
      >
        <View style={styles.modalFundo}>
          <View style={styles.modalCard}>
            <Text style={styles.modalTitulo}>Confirmar alterações</Text>
            <Text style={styles.modalTexto}>
              Deseja realmente salvar as alterações no seu perfil?
            </Text>

            <View style={styles.modalBotoes}>
              <TouchableOpacity
                style={[styles.modalBotao, styles.modalBotaoCancelar]}
                onPress={() => setConfirmando(false)}
                activeOpacity={0.85}
              >
                <Text style={styles.modalCancelarTexto}>Cancelar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.modalBotao, styles.modalBotaoConfirmar]}
                onPress={confirmarSalvar}
                activeOpacity={0.85}
              >
                <Text style={styles.modalConfirmarTexto}>Confirmar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const GREEN_DARK = '#0f5c2e';
const GREEN_MAIN = '#3fa63f';
const GREEN_LIGHT = '#c9e8bd';
const BG_CREAM = '#f5f4ea';
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
  inputBloqueado: {
    backgroundColor: '#ebebe4', 
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    fontSize: 15,
    color: '#333333',
  },
  mensagem: {
    fontSize: 13,
    fontWeight: '600',
    textAlign: 'center',
    marginBottom: 12,
  },
  mensagemErro: {
    color: '#c0392b',
  },
  mensagemOk: {
    color: GREEN_MAIN,
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
  cancelarButton: {
    width: '60%',
    height: 40,
    backgroundColor: GREEN_LIGHT,
    borderRadius: 30,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 12,
  },
  cancelarButtonText: {
    color: GREEN_DARK,
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
    marginTop: 10,
  },

  // modal
  modalFundo: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 24,
  },
  modalCard: {
    width: '100%',
    maxWidth: 360,
    backgroundColor: BG_CREAM,
    borderRadius: 24,
    padding: 24,
    alignItems: 'center',
  },
  modalTitulo: {
    fontSize: 18,
    fontWeight: '700',
    color: GREEN_DARK,
    marginBottom: 10,
  },
  modalTexto: {
    fontSize: 14,
    color: '#333333',
    textAlign: 'center',
    marginBottom: 22,
  },
  modalBotoes: {
    flexDirection: 'row',
    width: '100%',
    justifyContent: 'space-between',
  },
  modalBotao: {
    flex: 1,
    height: 40,
    borderRadius: 30,
    alignItems: 'center',
    justifyContent: 'center',
  },
  modalBotaoCancelar: {
    backgroundColor: GREEN_LIGHT,
    marginRight: 8,
  },
  modalBotaoConfirmar: {
    backgroundColor: GREEN_MAIN,
    marginLeft: 8,
  },
  modalCancelarTexto: {
    color: GREEN_DARK,
    fontSize: 15,
    fontWeight: '700',
  },
  modalConfirmarTexto: {
    color: '#ffffff',
    fontSize: 15,
    fontWeight: '700',
  },
});