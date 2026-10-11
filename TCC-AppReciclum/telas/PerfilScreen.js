import React from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  ScrollView,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';

import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';
import { useUsuario } from '../context/UsuarioContext'; 

// depois inserir dados da API 

function gerarIniciais(nome) {
  if (!nome) return '?';
  const partes = nome.trim().split(/\s+/);
  const primeira = partes[0][0];
  const ultima = partes.length > 1 ? partes[partes.length - 1][0] : '';
  return (primeira + ultima).toUpperCase();
}

const DENUNCIAS_COUNT = 7;

export default function HomeScreen({ navigation }) {

  const { usuario } = useUsuario();
  const nome = usuario?.nomeUsuario ?? 'Usuário';
  const iniciais = gerarIniciais(usuario?.nomeUsuario);


  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader height={100} />

      <SafeAreaView style={styles.body}>
        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          {/* Card do usuário */}
          <LinearGradient
            colors={['#1b6b3c', '#2f8f4e']}
            start={{ x: 0, y: 0 }}
            end={{ x: 1, y: 1 }}
            style={styles.profileCard}
          >
            

            <View style={styles.profileRow}>
                <View style={styles.avatar}>
                  <Text style={styles.avatarText}>{iniciais}</Text>
               </View>
              <View>
                  <Text style={styles.userName}>{nome}</Text>
               </View>
            </View>

            <TouchableOpacity  style={styles.settingsButton} activeOpacity={1} onPress={() => navigation.navigate('OpcoesPerfil')}>
              <Ionicons name="settings-outline" size={28} color="#ffffff" />
            </TouchableOpacity>
          </LinearGradient>

        
        </ScrollView>

        <BottomTabBar active="perfil" navigation={navigation} />
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: GREEN_DARK,
  },
  body: {
    flex: 1,
    backgroundColor: BG_CREAM,
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 20,
    paddingBottom: 20,
  },
  profileCard: {
    borderRadius: 22,
    padding: 20,
    marginBottom: 22,
  },
settingsButton: {
  position: 'absolute',
  top: 10,
  right: 10,
  width: 40,
  height: 40,
  borderRadius: 20,
  backgroundColor: 'rgba(255,255,255,0.2)',
  alignItems: 'center',
  justifyContent: 'center',
},
  profileRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 14,

  },
  avatar: {
    width: 46,
    height: 46,
    borderRadius: 23,
    backgroundColor: 'rgba(255,255,255,0.25)',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  avatarText: {
    color: '#ffffff',
    fontWeight: '700',
    fontSize: 15,
  },
  userName: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '700',
  },
  userLocal: {
    color: 'rgba(255,255,255,0.8)',
    fontSize: 12,
    marginTop: 2,
  },
  memberSince: {
    color: 'rgba(255,255,255,0.85)',
    fontSize: 12,
    textAlign: 'center',
  },
  menuList: {
    gap: 12,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 16,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  menuIconWrapper: {
    width: 30,
    height: 30,
    borderRadius: 15,
    backgroundColor: '#eaf7ea',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  menuLabel: {
    flex: 1,
    fontSize: 14,
    fontWeight: '600',
    color: '#333333',
  },
  menuBadge: {
    fontSize: 13,
    fontWeight: '700',
    color: GREEN_MAIN,
    marginRight: 8,
  },
});
