import React, { useEffect, useRef } from 'react';
import { Alert, Linking } from 'react-native';
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import axios from 'axios';

import AvaliacaoEvento from './telas/AvaliacaoEvento';
import OpcoesPerfilScreen from './telas/OpcoesPerfil';
import EditarPerfilScreen from './telas/editarPerfil';
import LoginScreen from './telas/LoginScreen';
import CadastroScreen from './telas/CadastroScreen';
import HomeScreen from './telas/HomeScreen';
import MapaScreen from './telas/MapaScreen';
import PerfilScreen from './telas/PerfilScreen';
import CadastrarEcopontoScreen from './telas/CadastrarEcopontoScreen';
import GerenciarEcopontosScreen from './telas/GerenciarEcopontosScreen';
import DenunciasScreen from './telas/DenunciasScreen';
import ReciclagemScreen from './telas/ReciclagemScreen';
import DashboardScreen from './telas/DashboardScreen';
import TelaMapa from './telas/TelaMapa';
import TelaCamera from './telas/TelaCamera';
import ConfirmarEmailScreen from './telas/ConfirmarEmailScreen';
import { API_URL, UsuarioProvider, useUsuario } from './context/UsuarioContext';

const Stack = createNativeStackNavigator();

function extrairDadosVerificacao(url) {
  if (!url) return null;

  try {
    const parsed = new URL(url);
    const match = parsed.pathname.match(/\/email\/verify\/(\d+)\/([^/?#]+)/i);

    if (match) {
      return { id: match[1], hash: match[2] };
    }

    const id = parsed.searchParams.get('id') || parsed.searchParams.get('user');
    const hash = parsed.searchParams.get('hash');

    if (id && hash) {
      return { id, hash };
    }
  } catch (err) {
    const match = url.match(/\/email\/verify\/(\d+)\/([^/?#]+)/i);
    if (match) {
      return { id: match[1], hash: match[2] };
    }
  }

  return null;
}

function AppContent() {
  const navigationRef = useRef(null);
  const { setUsuario } = useUsuario();

  useEffect(() => {
    const handleDeepLink = async (url) => {
      const dadosVerificacao = extrairDadosVerificacao(url);
      if (!dadosVerificacao) return;

      try {
        const resposta = await axios.get(
          `${API_URL}/email/verify/${dadosVerificacao.id}/${dadosVerificacao.hash}`
        );

        Alert.alert(
          'Sucesso',
          resposta.data?.message || resposta.data?.mensagem || 'Seu e-mail foi confirmado com sucesso.'
        );

        setUsuario((prev) => (prev ? { ...prev, email_verificado: true } : prev));

        if (navigationRef.current) {
          navigationRef.current.navigate('Login');
        }
      } catch (err) {
        Alert.alert(
          'Erro',
          err.response?.data?.message ||
            err.response?.data?.mensagem ||
            'Não foi possível confirmar o e-mail. Solicite um novo link.'
        );
      }
    };

    const subscription = Linking.addEventListener('url', ({ url }) => {
      handleDeepLink(url);
    });

    Linking.getInitialURL().then((initialUrl) => {
      if (initialUrl) {
        handleDeepLink(initialUrl);
      }
    });

    return () => subscription.remove();
  }, [setUsuario]);

  const linking = {
    prefixes: ['reciclum://'],
    config: {
      screens: {
        Login: 'login',
        ConfirmarEmail: 'confirmar-email',
        Home: 'home',
      },
    },
  };

  return (
    <>
      <StatusBar style="light" />
      <NavigationContainer ref={navigationRef} linking={linking}>
        <Stack.Navigator
          initialRouteName="Login"
          screenOptions={{ headerShown: false }}
        >
          <Stack.Screen name="Perfil" component={PerfilScreen} />
          <Stack.Screen name="Home" component={HomeScreen} />
          <Stack.Screen name="Login" component={LoginScreen} />
          <Stack.Screen name="Mapa" component={MapaScreen} />
          <Stack.Screen name="Cadastro" component={CadastroScreen} />
          <Stack.Screen name="ConfirmarEmail" component={ConfirmarEmailScreen} />
          <Stack.Screen name="CadastrarEcoponto" component={CadastrarEcopontoScreen} />
          <Stack.Screen name="GerenciarEcopontos" component={GerenciarEcopontosScreen} />
          <Stack.Screen name="Denuncias" component={DenunciasScreen} />
          <Stack.Screen name="Reciclagem" component={ReciclagemScreen} />
          <Stack.Screen name="Dashboard" component={DashboardScreen} />
          <Stack.Screen name="TelaMapa" component={TelaMapa} />
          <Stack.Screen name="TelaCamera" component={TelaCamera} />
          <Stack.Screen name="OpcoesPerfil" component={OpcoesPerfilScreen} />
          <Stack.Screen name="EditarPerfil" component={EditarPerfilScreen} />
          <Stack.Screen name="Evento" component={AvaliacaoEvento} />
        </Stack.Navigator>
      </NavigationContainer>
    </>
  );
}

export default function App() {
  return (
    <UsuarioProvider>
      <AppContent />
    </UsuarioProvider>
  );
}
