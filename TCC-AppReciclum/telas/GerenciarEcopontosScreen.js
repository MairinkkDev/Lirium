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
import { Ionicons } from '@expo/vector-icons';

import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';




const ECOPONTOS = [
  {
    id: '1',
    nome: 'Ecoponto Vila Nova',
    endereco: 'R. das Acácias, 120',
    distancia: '6,7 km',
    cor: '#3fa63f',
    corFundo: '#e6f5e6',
  },
  {
    id: '2',
    nome: 'Cooperativa Ciclo Justo',
    endereco: 'Av. Industrial, 2450',
    distancia: '3,4 km',
    cor: '#2b9fd6',
    corFundo: '#e4f2fb',
  },
  {
    id: '3',
    nome: 'Raiz Urbana — Sede',
    endereco: 'R. do Ipê, 77',
    distancia: '5,1 km',
    cor: '#d9743f',
    corFundo: '#fbece2',
  },
  {
    id: '4',
    nome: 'Mutirão Córrego Verde',
    endereco: 'Parque Linear, Vila Nova',
    distancia: '1,2 km',
    cor: '#e0b400',
    corFundo: '#fbf3d9',
  },
];

export default function GerenciarEcopontosScreen({ navigation }) {
  const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader height={90} />

      <SafeAreaView style={styles.body}>
        <View style={styles.titleBox}>
          <TouchableOpacity onPress={handleVoltar} activeOpacity={0.8}>
            <Ionicons name="chevron-back" size={22} color="#333333" />
          </TouchableOpacity>
          <Ionicons
            name="checkmark-circle-outline"
            size={18}
            color={GREEN_MAIN}
            style={{ marginLeft: 8, marginRight: 6 }}
          />
          <Text style={styles.title}>Gerenciar Ecopontos</Text>
        </View>

        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          {ECOPONTOS.map((item) => (
            <TouchableOpacity
              key={item.id}
              style={styles.card}
              activeOpacity={0.8}
              onPress={() => console.log('Abrir ecoponto', item.id)}
            >
              <View style={[styles.pinWrapper, { backgroundColor: item.corFundo }]}>
                <Ionicons name="location" size={18} color={item.cor} />
              </View>

              <View style={styles.cardInfo}>
                <Text style={styles.cardTitle}>{item.nome}</Text>
                <Text style={styles.cardSubtitle}>{item.endereco}</Text>
              </View>

              <Text style={styles.cardDistance}>{item.distancia}</Text>
            </TouchableOpacity>
          ))}
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
  titleBox: {
    flexDirection: 'row',
    alignItems: 'center',
    marginHorizontal: 20,
    marginTop: 18,
    marginBottom: 14,
    paddingVertical: 12,
    paddingHorizontal: 14,
    borderRadius: 14,
    backgroundColor: '#ffffff',
  },
  title: {
    fontSize: 15,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingBottom: 24,
  },
  card: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 14,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  pinWrapper: {
    width: 36,
    height: 36,
    borderRadius: 18,
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  cardInfo: {
    flex: 1,
  },
  cardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#333333',
  },
  cardSubtitle: {
    fontSize: 12,
    color: GRAY_TEXT,
    marginTop: 2,
  },
  cardDistance: {
    fontSize: 13,
    fontWeight: '600',
    color: '#333333',
  },
});
