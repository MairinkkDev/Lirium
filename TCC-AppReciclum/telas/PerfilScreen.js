import React, { useCallback, useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  ScrollView,
} from 'react-native';

import axios from 'axios';
import { useFocusEffect } from '@react-navigation/native';
import { useUsuario, API_URL } from '../context/UsuarioContext';

import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';

import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';


// depois inserir dados da API 

function gerarIniciais(nome) {
  if (!nome) return '?';
  const partes = nome.trim().split(/\s+/);
  const primeira = partes[0][0];
  const ultima = partes.length > 1 ? partes[partes.length - 1][0] : '';
  return (primeira + ultima).toUpperCase();
}


const MESES = [
  'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
  'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro',
];


function membroDesde(dataISO) {
  if (!dataISO) return '';
  const data = new Date(dataISO);
  if (Number.isNaN(data.getTime())) return '';
  return `Membro desde ${MESES[data.getMonth()]} de ${data.getFullYear()}`;
}

export default function HomeScreen({ navigation }) {

  const { usuario } = useUsuario();
  const nome = usuario?.nomeUsuario ?? 'Usuário';
  const iniciais = gerarIniciais(usuario?.nomeUsuario);
   const [resumo, setResumo] = useState({ acoes: 0, eventosParticipados: 0 });

  
  useFocusEffect(
    useCallback(() => {
      axios
        .get(`${API_URL}/me/resumo`)
        .then((resp) => setResumo(resp.data))
        .catch((err) => console.log('Erro ao buscar resumo do perfil:', err.message));
    }, [usuario?.idUsuario])
  );

  const menuItens = [
    {
      key: 'historico',
      label: 'Histórico de ações',
      icon: 'time-outline',
      onPress: () => navigation.navigate('HistoricoAcoes'),
    },
    {
      key: 'eventos',
      label: 'Meus eventos',
      icon: 'calendar-outline',
      onPress: () => navigation.navigate('MeusEventos'),
    },
  ];

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

              {/*   Parte do XP, não sei se ainda vamos fazer isso      
                <Text style={styles.xpLabel}>{PERFIL.xp} xp</Text>
                  <View style={styles.xpTrack}>
                    <View style={[styles.xpFill, { width: `${xpPct}%` }]} />
                  </View> 
            */}

            <Text style={styles.memberSince}>{membroDesde(usuario?.created_at)}</Text>

            <TouchableOpacity  style={styles.settingsButton} activeOpacity={1} onPress={() => navigation.navigate('OpcoesPerfil')}>
              <Ionicons name="settings-outline" size={28} color="#ffffff" />
            </TouchableOpacity>
          </LinearGradient>

                    {/* Estatísticas */}
          <View style={styles.statsRow}>
            <View style={styles.statCard}>
              <Text style={styles.statLabel}>AÇÕES</Text>
              <Text style={styles.statValue}>{resumo.acoes}</Text>
            </View>
            <View style={styles.statCard}>
              <Text style={styles.statLabel}>EVENTOS</Text>
              <Text style={styles.statValue}>{resumo.eventosParticipados} Kg</Text>
            </View>
          </View>

          {/* Menu de opções */}
          <View style={styles.menuList}>
            {menuItens.map((item) => (
              <TouchableOpacity
                key={item.key}
                style={styles.menuItem}
                onPress={item.onPress}
                activeOpacity={0.8}
              >
                <View style={styles.menuIconWrapper}>
                  <Ionicons name={item.icon} size={18} color={GREEN_MAIN} />
                </View>
                <Text style={styles.menuLabel}>{item.label}</Text>
                <Ionicons name="chevron-forward" size={18} color={GRAY_TEXT} />
              </TouchableOpacity>
            ))}
          </View>

        
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

    xpLabel: {
    color: '#ffffff',
    fontSize: 11,
    marginBottom: 4,
  },
  xpTrack: {
    height: 3,
    borderRadius: 2,
    backgroundColor: 'rgba(255,255,255,0.35)',
    overflow: 'hidden',
    marginBottom: 12,
  },
  xpFill: {
    height: '100%',
    backgroundColor: '#ffffff',
  },
  statsRow: {
    flexDirection: 'row',
    gap: 14,
    marginBottom: 22,
  },
  statCard: {
    flex: 1,
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 12,
    paddingHorizontal: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  statLabel: {
    fontSize: 11,
    fontWeight: '600',
    color: GRAY_TEXT,
    marginBottom: 4,
  },
  statValue: {
    fontSize: 18,
    fontWeight: '800',
    color: '#1b2b21',
  },
});
