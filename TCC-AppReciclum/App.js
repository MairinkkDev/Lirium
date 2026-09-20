import React from 'react';
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import LoginScreen from './telas/LoginScreen';
import CadastroScreen from './telas/CadastroScreen';
import HomeScreen from './telas/HomeScreen';
import MapaScreen from './telas/MapaScreen';
import PerfilScreen from './telas/PerfilScreen';
import CadastrarEcopontoScreen from './telas/CadastrarEcopontoScreen';
import GerenciarEcopontosScreen from './telas/GerenciarEcopontosScreen';
import DenunciasScreen from './telas/DenunciasScreen';
import ReciclagemScreen from './telas/ReciclagemScreen'; 

const Stack = createNativeStackNavigator();

export default function App() {
  return (
    <>
      <StatusBar style="light" />
      <NavigationContainer>
        <Stack.Navigator
          initialRouteName="Login"
          screenOptions={{ headerShown: false }}
        >
          <Stack.Screen name="Perfil" component={PerfilScreen} />
          <Stack.Screen name="Home" component={HomeScreen} />
          <Stack.Screen name="Login" component={LoginScreen} />
          <Stack.Screen name="Mapa" component={MapaScreen} />
          <Stack.Screen name="Cadastro" component={CadastroScreen} />
          <Stack.Screen name="CadastrarEcoponto" component={CadastrarEcopontoScreen} />
          <Stack.Screen name="GerenciarEcopontos" component={GerenciarEcopontosScreen} />
          <Stack.Screen name="Denuncias" component={DenunciasScreen} />
          <Stack.Screen name="Reciclagem" component={ReciclagemScreen} />
        </Stack.Navigator>
      </NavigationContainer>
    </>
  );
}