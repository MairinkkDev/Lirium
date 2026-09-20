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
import GaugeChart from './components/GaugeChart';

// Dados estáticos por enquanto — troque pelas chamadas de
// /dashboard/usuarios/total, /dashboard/usuarios/ativos, etc. quando integrar a API
const DADOS = {
  usuariosTotal: 67345,
  usuariosOnline: 12645,
  suspenso: 684,
  cooperativasAtivas: 28504,
  cooperativasSuspensas: 51,
};

export default function DashboardScreen({ navigation }) {
  const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader height={90} />

      <SafeAreaView style={styles.body}>
        {/* Cabeçalho da tela com voltar + título */}
        <View style={styles.titleBox}>
          <TouchableOpacity onPress={handleVoltar} activeOpacity={0.8}>
            <Ionicons name="chevron-back" size={22} color="#333333" />
          </TouchableOpacity>
          <Ionicons
            name="bar-chart-outline"
            size={18}
            color={GREEN_MAIN}
            style={{ marginLeft: 8, marginRight: 6 }}
          />
          <Text style={styles.title}>Dashboard</Text>
        </View>

        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          {/* Usuários totais */}
          <Text style={styles.sectionLabel}>Usuários</Text>
          <Text style={styles.bigNumber}>
            {DADOS.usuariosTotal.toLocaleString('pt-BR')}
          </Text>

          {/* Gauge de usuários online */}
          <View style={styles.gaugeWrapper}>
            <GaugeChart
              value={DADOS.usuariosOnline}
              max={DADOS.usuariosTotal}
              label="Online"
            />
          </View>

          {/* Suspenso */}
          <TouchableOpacity
            style={styles.row}
            activeOpacity={0.8}
            onPress={() => console.log('Abrir usuários suspensos')}
          >
            <View style={[styles.dot, { backgroundColor: '#e2607a' }]} />
            <Text style={styles.rowValue}>
              {DADOS.suspenso.toLocaleString('pt-BR')}
            </Text>
            <View style={{ flex: 1 }} />
            <Ionicons name="chevron-forward" size={18} color={GRAY_TEXT} />
          </TouchableOpacity>

          {/* Cooperativas */}
          <Text style={[styles.sectionLabel, { marginTop: 28 }]}>Cooperativas</Text>

          <View style={styles.statsRow}>
            <View style={styles.statColumn}>
              <Text style={styles.statLabel}>Ativa</Text>
              <TouchableOpacity
                style={styles.statPill}
                activeOpacity={0.8}
                onPress={() => console.log('Abrir cooperativas ativas')}
              >
                <View style={[styles.dot, { backgroundColor: GREEN_MAIN }]} />
                <Text style={styles.statValue}>
                  {DADOS.cooperativasAtivas.toLocaleString('pt-BR')}
                </Text>
                <Ionicons name="chevron-forward" size={16} color={GRAY_TEXT} />
              </TouchableOpacity>
            </View>

            <View style={styles.statColumn}>
              <Text style={styles.statLabel}>Suspensa</Text>
              <TouchableOpacity
                style={styles.statPill}
                activeOpacity={0.8}
                onPress={() => console.log('Abrir cooperativas suspensas')}
              >
                <View style={[styles.dot, { backgroundColor: '#e2607a' }]} />
                <Text style={styles.statValue}>
                  {DADOS.cooperativasSuspensas.toLocaleString('pt-BR')}
                </Text>
                <Ionicons name="chevron-forward" size={16} color={GRAY_TEXT} />
              </TouchableOpacity>
            </View>
          </View>

          {/* Atividades recentes */}
          <Text style={[styles.sectionLabel, { marginTop: 28, marginBottom: 12 }]}>
            Atividades recentes
          </Text>

          <View style={styles.emptyState}>
            <Text style={styles.emptyStateText}>Nenhuma atividade recente.</Text>
          </View>
        </ScrollView>

        <BottomTabBar active="inicio" navigation={navigation} />
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
    paddingVertical: 14,
    paddingHorizontal: 16,
    borderRadius: 16,
    backgroundColor: '#ffffff',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  title: {
    fontSize: 15,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingBottom: 24,
    alignItems: 'center',
  },
  sectionLabel: {
    fontSize: 14,
    color: '#333333',
    fontWeight: '600',
    marginBottom: 4,
  },
  bigNumber: {
    fontSize: 26,
    fontWeight: '800',
    color: '#333333',
    marginBottom: 6,
  },
  gaugeWrapper: {
    marginBottom: 10,
  },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    width: '100%',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 16,
    marginTop: 8,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  rowValue: {
    fontSize: 14,
    fontWeight: '700',
    color: '#333333',
  },
  dot: {
    width: 9,
    height: 9,
    borderRadius: 4.5,
    marginRight: 10,
  },
  statsRow: {
    flexDirection: 'row',
    width: '100%',
    justifyContent: 'space-between',
  },
  statColumn: {
    flex: 1,
    alignItems: 'center',
  },
  statLabel: {
    fontSize: 13,
    color: '#333333',
    fontWeight: '600',
    marginBottom: 8,
  },
  statPill: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 20,
    paddingVertical: 10,
    paddingHorizontal: 14,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  statValue: {
    fontSize: 13,
    fontWeight: '700',
    color: '#333333',
    marginRight: 6,
  },
  emptyState: {
    width: '100%',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    paddingVertical: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  emptyStateText: {
    fontSize: 13,
    color: GRAY_TEXT,
  },
});
