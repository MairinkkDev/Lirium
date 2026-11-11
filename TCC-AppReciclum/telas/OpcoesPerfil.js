import React from 'react';
import {
  SafeAreaView,
  View,
  TouchableOpacity,
  Text,
  StatusBar,
  StyleSheet,
} from 'react-native';
import { useUsuario } from '../context/UsuarioContext';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import AppHeader, { BG_CREAM } from './components/AppHeader';

export default function OpcoesPerfilScreen({ navigation }) {
  const { sair } = useUsuario();

  const handleSair = () => {
    sair().then(() => {
      navigation.reset({ index: 0, routes: [{ name: 'Login' }] });
    });
  };

   const handleVoltar = () => {
  if (navigation) navigation.goBack();
  console.log('Voltar');
};

  const menuItens = [
    {
      key: 'perfil',
      label: 'Perfil e Conta',
      icon: <Ionicons name="create" size={22} color="#111111" />,
      onPress: () => navigation.navigate('EditarPerfil'),
    },
    {
      key: 'exibicao',
      label: 'Preferências de Exibição',
      icon: <MaterialCommunityIcons name="palette" size={22} color="#111111" />,
      onPress: () => console.log('TODO: Preferências de Exibição'),
    },
    {
      key: 'privacidade',
      label: 'Privacidade e Segurança',
      icon: <Ionicons name="lock-closed" size={22} color="#111111" />,
      onPress: () => console.log('TODO: Privacidade e Segurança'),
    },
    {
      key: 'notificacoes',
      label: 'Notificações',
      icon: <Ionicons name="notifications" size={22} color="#111111" />,
      onPress: () => console.log('TODO: Notificações'),
    },
    {
      key: 'sobre',
      label: 'Sobre',
      icon: <Ionicons name="information-circle" size={22} color="#111111" />,
      onPress: () => console.log('TODO: Sobre'),
    },
  ];

    return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader />

      <SafeAreaView style={styles.body}>
        {/* Voltar */}
        <TouchableOpacity
          style={styles.circleButton}
          onPress={handleVoltar}
          activeOpacity={0.85}
        >
          <Ionicons name="chevron-back" size={20} color={GREEN_DARK} />
        </TouchableOpacity>

        {/* Opções */}
        <View style={styles.menuList}>
          {menuItens.map((item) => (
            <TouchableOpacity
              key={item.key}
              style={styles.menuItem}
              onPress={item.onPress}
              activeOpacity={0.8}
            >
              <View style={styles.menuIcon}>{item.icon}</View>
              <Text style={styles.menuLabel}>{item.label}</Text>
            </TouchableOpacity>
          ))}
        </View>

        <View style={{ flex: 1 }} />

        {/* Sair */}
        <TouchableOpacity
          style={[styles.circleButton, styles.sairButton]}
          onPress={handleSair}
          activeOpacity={0.85}
        >
          <Ionicons name="log-out-outline" size={20} color="#b3261e" />
        </TouchableOpacity>
      </SafeAreaView>
    </View>
  );
}



const GREEN_DARK = '#0f5c2e';
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: GREEN_DARK,
  },
  body: {
    flex: 1,
    backgroundColor: BG_CREAM,
  },
  circleButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#e3e3dc',
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 12,
    marginLeft: 14,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  sairButton: {
    alignSelf: 'center',
    marginLeft: 0,
    marginBottom: 28,
  },
  menuList: {
    paddingHorizontal: 20,
    marginTop: 18,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.08,
    shadowRadius: 4,
    elevation: 3,
  },
  menuIcon: {
    width: 40,
    alignItems: 'center',
    marginRight: 12,
  },
  menuLabel: {
    flex: 1,
    fontSize: 14,
    fontWeight: '700',
    color: '#111111',
  },
});