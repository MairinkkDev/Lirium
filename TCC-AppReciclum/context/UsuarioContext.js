import React, { createContext, useContext, useEffect, useState } from 'react';
import * as SecureStore from 'expo-secure-store';
import axios from 'axios';

export const API_URL = 'http://192.168.0.225:8000/api';
const CHAVE_TOKEN = 'token';

axios.defaults.headers.common.Accept = 'application/json';

function aplicarToken(token) {
  if (token) {
    axios.defaults.headers.common.Authorization = `Bearer ${token}`;
  } else {
    delete axios.defaults.headers.common.Authorization;
  }
}

const UsuarioContext = createContext(null);

export function UsuarioProvider({ children }) {
  const [usuario, setUsuario] = useState(null);
  const [carregando, setCarregando] = useState(true);

  useEffect(() => {
    async function restaurarSessao() {
      try {
        const token = await SecureStore.getItemAsync(CHAVE_TOKEN);
        if (token) {
          aplicarToken(token);
          const resp = await axios.get(`${API_URL}/me`);
          setUsuario(resp.data);
        }
      } catch (err) {
        console.log('Sessão inválida. Limpando token.', err?.response?.data || err.message);
        await SecureStore.deleteItemAsync(CHAVE_TOKEN);
        aplicarToken(null);
        setUsuario(null);
      } finally {
        setCarregando(false);
      }
    }

    restaurarSessao();
  }, []);

  const entrar = async (token, dadosUsuario) => {
    await SecureStore.setItemAsync(CHAVE_TOKEN, token);
    aplicarToken(token);
    setUsuario(dadosUsuario || null);
  };

  const sair = async () => {
    try {
      await axios.post(`${API_URL}/logout`);
    } catch (err) {
      console.log('Logout no servidor falhou (segue o logout local):', err.message);
    }

    await SecureStore.deleteItemAsync(CHAVE_TOKEN);
    aplicarToken(null);
    setUsuario(null);
  };

  return (
    <UsuarioContext.Provider value={{ usuario, carregando, entrar, sair, setUsuario }}>
      {children}
    </UsuarioContext.Provider>
  );
}

export function useUsuario() {
  return useContext(UsuarioContext);
}