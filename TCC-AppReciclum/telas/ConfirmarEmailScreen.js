import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
} from 'react-native';
import axios from 'axios';

import { API_URL, useUsuario } from '../context/UsuarioContext';

export default function ConfirmarEmailScreen({ navigation }) {
  const { usuario } = useUsuario();
  const [enviando, setEnviando] = useState(false);

  const reenviarEmail = async () => {
    setEnviando(true);

    try {
      await axios.post(`${API_URL}/email/verification-notification`);
      Alert.alert('E-mail reenviado', 'Verifique sua caixa de entrada e a pasta de spam.');
    } catch (err) {
      Alert.alert(
        'Erro',
        err.response?.data?.message ||
          err.response?.data?.mensagem ||
          'Não foi possível reenviar o e-mail agora.'
      );
    } finally {
      setEnviando(false);
    }
  };

  const voltarAoLogin = () => {
    navigation.reset({
      index: 0,
      routes: [{ name: 'Login' }],
    });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Confirmar e-mail</Text>
      <Text style={styles.description}>
        Confirme o endereço abaixo para liberar o acesso ao app.
      </Text>

      <Text style={styles.email}>
        {usuario?.emailUsuario || usuario?.email || 'Seu e-mail'}
      </Text>

      <TouchableOpacity
        style={styles.primaryButton}
        onPress={reenviarEmail}
        disabled={enviando}
      >
        {enviando ? (
          <ActivityIndicator color="#ffffff" />
        ) : (
          <Text style={styles.primaryButtonText}>Reenviar e-mail</Text>
        )}
      </TouchableOpacity>

      <TouchableOpacity style={styles.secondaryButton} onPress={voltarAoLogin}>
        <Text style={styles.secondaryButtonText}>Voltar ao login</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 24,
    backgroundColor: '#f3f7f2',
  },
  title: {
    fontSize: 28,
    fontWeight: '700',
    color: '#0f5c2e',
    marginBottom: 12,
  },
  description: {
    fontSize: 16,
    color: '#3b3b3b',
    textAlign: 'center',
    marginBottom: 18,
  },
  email: {
    fontSize: 18,
    fontWeight: '600',
    color: '#1f5d2b',
    marginBottom: 28,
    textAlign: 'center',
  },
  primaryButton: {
    width: '100%',
    backgroundColor: '#2e8b57',
    borderRadius: 14,
    paddingVertical: 14,
    alignItems: 'center',
    marginBottom: 14,
  },
  primaryButtonText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '700',
  },
  secondaryButton: {
    width: '100%',
    backgroundColor: '#dfe9e1',
    borderRadius: 14,
    paddingVertical: 14,
    alignItems: 'center',
  },
  secondaryButtonText: {
    color: '#0f5c2e',
    fontSize: 16,
    fontWeight: '600',
  },
});
