// Salve em: context/UsuarioContext.js (substitui a versão anterior)
import React, { createContext, useContext, useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

export const API_URL = 'http://localhost:8000/api';
const CHAVE_TOKEN = 'token';

// Toda requisição pede JSON (sem isso o Laravel pode redirecionar em vez de dar 401)
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

  // Ao abrir o app: se existe token salvo, busca o usuário em /me
  useEffect(() => {
    async function restaurarSessao() {
      try {
        const token = await AsyncStorage.getItem(CHAVE_TOKEN);
        if (token) {
          aplicarToken(token);
          const resp = await axios.get(`${API_URL}/me`);
          setUsuario(resp.data);
        }
      } catch (err) {
        // token inválido, expirado ou API fora do ar: limpa e pede login de novo
        await AsyncStorage.removeItem(CHAVE_TOKEN);
        aplicarToken(null);
        setUsuario(null);
      } finally {
        setCarregando(false);
      }
    }
    restaurarSessao();
  }, []);

  // Chamado pela LoginScreen quando o login dá certo (200)
  const entrar = async (token, dadosUsuario) => {
    await AsyncStorage.setItem(CHAVE_TOKEN, token);
    aplicarToken(token);
    setUsuario(dadosUsuario);
  };

  // Chamado pelo botão de sair
  const sair = async () => {
    try {
      await axios.post(`${API_URL}/logout`); // apaga o token no servidor
    } catch (err) {
      console.log('Logout no servidor falhou (segue o logout local):', err.message);
    }
    await AsyncStorage.removeItem(CHAVE_TOKEN);
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