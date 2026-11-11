import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  ScrollView,
  ActivityIndicator,
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

import axios from 'axios';
import { API_URL } from '../context/UsuarioContext';

import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';

const FILTROS = [
  { key: 'todos', label: 'Todos' },
  { key: 'evento', label: 'Evento' },
  { key: 'denuncia', label: 'Denúncia' },
  { key: 'reciclagem', label: 'Reciclagem' },
];

function formatarDataHora(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return '';
  const dois = (n) => String(n).padStart(2, '0');
  return `${dois(d.getDate())}/${dois(d.getMonth() + 1)}/${d.getFullYear()} às ${dois(d.getHours())}:${dois(d.getMinutes())}`;
}

// Ícone do nó da linha do tempo e da miniatura de cada tipo de ação
// (para usar foto de verdade na miniatura, troque o <Icone> por um <Image>)
function IconePorTipo({ tipo, size, color }) {
  if (tipo === 'reciclagem') {
    return <MaterialCommunityIcons name="recycle" size={size} color={color} />;
  }
  if (tipo === 'ong') {
    return <Ionicons name="heart-outline" size={size} color={color} />;
  }
    if (tipo === 'denuncia') {
    return <Ionicons name="flag-outline" size={size} color={color} />;
  }
  return <Ionicons name="calendar-outline" size={size} color={color} />;
}

export default function HistoricoAcoesScreen({ navigation }) {
  const [filtro, setFiltro] = useState('todos');
    const [acoes, setAcoes] = useState([]);
  const [carregando, setCarregando] = useState(true);
  const [erro, setErro] = useState('');

  function carregarHistorico() {
    setCarregando(true);
    setErro('');
    axios
      .get(API_URL + '/me/historico')
      .then((resp) => setAcoes(resp.data))
      .catch((err) => {
        console.log('Erro ao buscar histórico:', err.message);
        setErro('Não foi possível carregar o histórico.');
      })
      .finally(() => setCarregando(false));
  }

  useEffect(() => {
    carregarHistorico();
  }, []);

  const acoesFiltradas =
    filtro === 'todos' ? acoes : acoes.filter((a) => a.tipo === filtro);

      const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

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

        {/* Filtros */}
        <View style={styles.filtrosRow}>
          {FILTROS.map((f) => {
            const ativo = f.key === filtro;
            return (
              <TouchableOpacity
                key={f.key}
                style={[styles.chip, ativo && styles.chipAtivo]}
                onPress={() => setFiltro(f.key)}
                activeOpacity={0.8}
              >
                <Text style={[styles.chipTexto, ativo && styles.chipTextoAtivo]}>
                  {f.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </View>

        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
                    {carregando ? (
            <ActivityIndicator size="large" color={GREEN_MAIN} style={{ marginTop: 30 }} />
          ) : erro ? (
            <View style={{ alignItems: 'center', marginTop: 30 }}>
              <Text style={styles.erroTexto}>{erro}</Text>
              <TouchableOpacity onPress={carregarHistorico} style={{ marginTop: 10 }}>
                <Text style={styles.tentarDeNovo}>Tentar de novo</Text>
              </TouchableOpacity>
            </View>
          ) : acoesFiltradas.length === 0 ? (
            <Text style={styles.vazioTexto}>Nenhuma ação encontrada.</Text>
          ) : (
            <View>
              {/* Linha vertical da timeline */}
              <View style={styles.linha} />

              {acoesFiltradas.map((acao) => (
                <View key={acao.id} style={styles.item}>
                  <View style={styles.no}>
                    <IconePorTipo tipo={acao.tipo} size={16} color={GREEN_MAIN} />
                  </View>

                  <TouchableOpacity
                    style={styles.card}
                    activeOpacity={0.8}
                    onPress={() => console.log('Abrir ação', acao.id)}
                  >
                    <View style={styles.miniatura}>
                      <IconePorTipo tipo={acao.tipo} size={20} color={GREEN_MAIN} />
                    </View>
                                       <View style={{ flex: 1 }}>
                      <Text style={styles.cardTexto} numberOfLines={2}>
                        {acao.texto}
                      </Text>
                      <Text style={styles.dataTexto}>{formatarDataHora(acao.data)}</Text>
                    </View>
                  </TouchableOpacity>
                </View>
              ))}
            </View>
          )}
        </ScrollView>

        <BottomTabBar active="perfil" navigation={navigation} />
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0f5c2e',
  },
  body: {
    flex: 1,
    backgroundColor: BG_CREAM,
  },
  filtrosRow: {
    flexDirection: 'row',
    gap: 8,
    paddingHorizontal: 20,
    paddingTop: 16,
    paddingBottom: 18,
  },
  chip: {
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 12,
    backgroundColor: '#dcebd7',
  },
  chipAtivo: {
    backgroundColor: '#0f7a3a',
  },
  chipTexto: {
    fontSize: 10,
    fontWeight: '600',
    color: '#7a9a78',
  },
  chipTextoAtivo: {
    color: '#ffffff',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingBottom: 24,
  },
  vazioTexto: {
    fontSize: 13,
    color: GRAY_TEXT,
    textAlign: 'center',
    marginTop: 30,
  },
  linha: {
    position: 'absolute',
    left: 15,
    top: 0,
    bottom: 0,
    width: 2,
    backgroundColor: '#d9ddd0',
  },
  item: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 14,
  },
  no: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: '#d6ecd0',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 10,
  },
  card: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 14,
    paddingVertical: 10,
    paddingHorizontal: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.08,
    shadowRadius: 4,
    elevation: 3,
  },
  miniatura: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: '#e6f5e6',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 10,
  },
  cardTexto: {
    fontSize: 12,
    fontWeight: '700',
    color: GREEN_MAIN,
    dataTexto: { fontSize: 10, color: GRAY_TEXT, marginTop: 2 },
    erroTexto: { fontSize: 13, color: '#c0392b', textAlign: 'center' },
    tentarDeNovo: { fontSize: 13, fontWeight: '700', color: GREEN_MAIN },
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
});