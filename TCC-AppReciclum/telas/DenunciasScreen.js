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


const RESUMO = {
  total: 7,
  pendentes: 4,
  urgentes: 3,
};

const DENUNCIAS = [
  {
    id: '0684',
    usuario: 'João Silva',
    motivo: 'Conteúdo falso',
    data: 'Há 1 hora',
  },
  {
    id: '0683',
    cooperativa: 'EcoVida',
    motivo: 'Irregularidade',
    data: '28/08/2026 às 19:34',
  },
];

export default function DenunciasScreen({ navigation }) {
  const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader height={90} />

      <SafeAreaView style={styles.body}>
        
        <View style={styles.titleRow}>
          <TouchableOpacity onPress={handleVoltar} activeOpacity={0.8}>
            <Ionicons name="chevron-back" size={22} color="#333333" />
          </TouchableOpacity>
          <Ionicons
            name="person-outline"
            size={18}
            color={GREEN_MAIN}
            style={{ marginLeft: 8, marginRight: 6 }}
          />
          <Text style={styles.title}>Denúncias</Text>
        </View>

        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          
          <View style={styles.totalRow}>
            <Text style={styles.totalNumber}>
              {String(RESUMO.total).padStart(2, '0')}
            </Text>
            <Text style={styles.totalLabel}>denúncias registradas</Text>
          </View>

          
          <View style={styles.statsRow}>
            <View style={styles.statCard}>
              <View style={styles.statHeader}>
                <View style={[styles.statDot, { backgroundColor: '#e2825f' }]} />
              </View>
              <Text style={styles.statNumber}>
                {String(RESUMO.pendentes).padStart(2, '0')}
              </Text>
              <Text style={styles.statLabel}>Pendentes</Text>
            </View>

            <View style={styles.statCard}>
              <View style={styles.statHeader}>
                <View style={[styles.statDot, { backgroundColor: '#e2607a' }]} />
              </View>
              <Text style={styles.statNumber}>
                {String(RESUMO.urgentes).padStart(2, '0')}
              </Text>
              <Text style={styles.statLabel}>Urgentes</Text>
            </View>
          </View>

          
          <Text style={styles.sectionTitle}>Pré-visualização de denúncias</Text>

          {DENUNCIAS.map((item) => (
            <TouchableOpacity
              key={item.id}
              style={styles.denunciaCard}
              activeOpacity={0.8}
              onPress={() => console.log('Abrir denúncia', item.id)}
            >
              <View style={styles.denunciaDot} />

              <View style={styles.denunciaInfo}>
                <Text style={styles.denunciaTitle}>{`Denúncia #${item.id}`}</Text>
                {item.usuario ? (
                  <Text style={styles.denunciaText}>Usuário: {item.usuario}</Text>
                ) : null}
                {item.cooperativa ? (
                  <Text style={styles.denunciaText}>
                    Cooperativa: {item.cooperativa}
                  </Text>
                ) : null}
                <Text style={styles.denunciaText}>Motivo: {item.motivo}</Text>
                <Text style={styles.denunciaText}>Data: {item.data}</Text>
              </View>

              <Ionicons name="chevron-forward" size={18} color={GRAY_TEXT} />
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
  titleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingTop: 18,
    paddingBottom: 8,
  },
  title: {
    fontSize: 16,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingBottom: 24,
  },
  totalRow: {
    flexDirection: 'row',
    alignItems: 'baseline',
    marginTop: 6,
    marginBottom: 18,
  },
  totalNumber: {
    fontSize: 28,
    fontWeight: '800',
    color: '#333333',
    marginRight: 10,
  },
  totalLabel: {
    fontSize: 14,
    color: '#333333',
    fontWeight: '500',
  },
  statsRow: {
    flexDirection: 'row',
    gap: 14,
    marginBottom: 24,
  },
  statCard: {
    flex: 1,
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 14,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  statHeader: {
    marginBottom: 6,
  },
  statDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
  },
  statNumber: {
    fontSize: 20,
    fontWeight: '800',
    color: '#333333',
    marginBottom: 2,
  },
  statLabel: {
    fontSize: 12,
    color: GRAY_TEXT,
    fontWeight: '600',
  },
  sectionTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 12,
  },
  denunciaCard: {
    flexDirection: 'row',
    alignItems: 'flex-start',
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
  denunciaDot: {
    width: 9,
    height: 9,
    borderRadius: 4.5,
    backgroundColor: '#e2607a',
    marginTop: 4,
    marginRight: 10,
  },
  denunciaInfo: {
    flex: 1,
  },
  denunciaTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 4,
  },
  denunciaText: {
    fontSize: 12,
    color: GRAY_TEXT,
    marginBottom: 1,
  },
});
