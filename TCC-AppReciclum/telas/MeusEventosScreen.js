import React, { useCallback, useState } from 'react';
import {
  SafeAreaView,
  ScrollView,
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert, Platform,
} from 'react-native';

import axios from 'axios';
import { useFocusEffect } from '@react-navigation/native';
import { useUsuario, API_URL } from '../context/UsuarioContext';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import BottomTabBar from './components/BottomTabBar';
import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';

// mesmas cores da tela AvaliacaoEvento

const COR = {
  fundo: '#f5f4ea',
  card: '#ffffff',
  caixa: '#edf1e4',
  verde: '#0a7d3e',
  verdeClaro: '#5aab4f',
  texto: '#13261a',
  textoSuave: '#6b7468',
};

function avisar(titulo, mensagem) {
  if (Platform.OS === 'web') {
    window.alert(titulo + '\n\n' + mensagem);
  } else {
    Alert.alert(titulo, mensagem);
  }
}

function confirmar(titulo, mensagem, textoConfirmar, aoConfirmar) {
  if (Platform.OS === 'web') {
    if (window.confirm(titulo + '\n\n' + mensagem)) aoConfirmar();
    return;
  }
  Alert.alert(titulo, mensagem, [
    { text: 'Cancelar', style: 'cancel' },
    { text: textoConfirmar, style: 'destructive', onPress: aoConfirmar },
  ]);
}

function formatarData(texto) {
  if (!texto) return '';
  return texto.substring(8, 10) + '/' + texto.substring(5, 7) + '/' + texto.substring(0, 4);
}
function formatarHora(texto) {
  if (!texto) return '';
  return texto.substring(11, 16);
}
function nomeDaInstituicao(evento) {
  if (evento.instituicoes && evento.instituicoes.length > 0) {
    return evento.instituicoes[0].nomeInstituicao;
  }
  return '';
}

// Faixa verde + informações do evento (igual ao card da AvaliacaoEvento)
function CartaoEvento({ evento, botao }) {
  return (
    <View style={styles.cardSombra}>
      <View style={styles.card}>
        <LinearGradient
          colors={[COR.verde, COR.verdeClaro]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 0 }}
          style={styles.faixa}
        >
          <Text style={styles.faixaTexto}>
            {String(evento.statusEvento).replace('_', ' ').toUpperCase()}
          </Text>
        </LinearGradient>

        <View style={styles.corpo}>
          <Text style={styles.nome}>{evento.nomeEvento}</Text>
          <Text style={styles.instituicao}>{nomeDaInstituicao(evento)}</Text>

          <View style={{ marginTop: 14 }}>
            <View style={styles.linhaInfo}>
              <Ionicons name="calendar-outline" size={18} color={COR.textoSuave} />
              <Text style={styles.linhaTexto}>{formatarData(evento.dataInicioEvento)}</Text>
            </View>
            <View style={styles.linhaInfo}>
              <Ionicons name="time-outline" size={18} color={COR.textoSuave} />
              <Text style={styles.linhaTexto}>{formatarHora(evento.dataInicioEvento)} – {formatarHora(evento.dataFimEvento)}</Text>
            </View>
            <View style={styles.linhaInfo}>
              <Ionicons name="location-outline" size={18} color={COR.textoSuave} />
              <Text style={styles.linhaTexto}>{evento.logradouroEnderecoEvento}, {evento.bairroEnderecoEvento}</Text>
            </View>
          </View>

          <Text style={styles.descricao}>{evento.descricaoEvento}</Text>

          {botao}
        </View>
      </View>
    </View>
  );
}

export default function MeusEventosScreen({ navigation }) {
  const { usuario } = useUsuario();

  const [aba, setAba] = useState('proximos');
  const [eventos, setEventos] = useState([]);
  const [confirmados, setConfirmados] = useState([]);
  const [minhasNotas, setMinhasNotas] = useState({});
  const [carregando, setCarregando] = useState(true);
  const [erro, setErro] = useState('');

  const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

  function carregarDados() {
    setCarregando(true);
    setErro('');

    Promise.all([
      axios.get(API_URL + '/eventos'),
      axios.get(API_URL + '/minhas-participacoes'),
      axios.get(API_URL + '/evento-avaliacoes'),
    ])
      .then(([respEventos, respParticipacoes, respAvaliacoes]) => {
        setEventos(respEventos.data);
        setConfirmados(respParticipacoes.data);

        let lista = respAvaliacoes.data;
        if (!Array.isArray(lista)) {
          lista = lista.data || [];
        }
        const notas = {};
        lista.forEach((a) => {
          if (Number(a.idUsuario) === Number(usuario.idUsuario)) {
            notas[a.idEvento] = a.notaAvaliacao;
          }
        });
        setMinhasNotas(notas);
      })
      .catch((err) => {
        console.log('Erro ao buscar meus eventos:', err.message);
        setErro('Não foi possível carregar seus eventos.');
      })
      .finally(() => {
        setCarregando(false);
      });
  }

  useFocusEffect(
    useCallback(() => {
      carregarDados();
    }, [usuario?.idUsuario])
  );

 function cancelarPresenca(evento) {
  confirmar(
    'Cancelar presença',
    'Deseja cancelar sua presença em "' + evento.nomeEvento + '"?',
    'Sim, cancelar',
    () => {
      axios
        .delete(API_URL + '/eventos/' + evento.idEvento + '/participar')
        .then(() => {
          setConfirmados((atual) => atual.filter((x) => x !== evento.idEvento));
        })
        .catch((err) => {
          console.log('Erro ao cancelar presença:', err.response ? err.response.data : err.message);
          avisar('Erro', 'Não foi possível cancelar a presença.');
        });
    }
  );
}

  const proximos = eventos.filter(
    (e) =>
      (e.statusEvento === 'agendado' || e.statusEvento === 'em_andamento') &&
      confirmados.includes(e.idEvento)
  );

  const participei = eventos.filter(
    (e) => e.statusEvento === 'concluido' && confirmados.includes(e.idEvento)
  );

  return (
    <SafeAreaView style={styles.tela}>
      <AppHeader height={100} />
      <TouchableOpacity
        style={styles.circleButton}
        onPress={handleVoltar}
        activeOpacity={0.85}
      >
        <Ionicons name="chevron-back" size={20} color={GREEN_DARK} />
      </TouchableOpacity>
      

      <ScrollView contentContainerStyle={styles.conteudo}>
        <Text style={styles.titulo}>Meus eventos</Text>
        <Text style={styles.subtitulo}>Ações ambientais perto de você</Text>

        <TouchableOpacity onPress={carregarDados}>
          <Text style={styles.atualizar}>Atualizar lista</Text>
        </TouchableOpacity>

        <View style={styles.trilho}>
          <TouchableOpacity
            style={[styles.aba, aba === 'proximos' ? styles.abaAtiva : null]}
            onPress={() => setAba('proximos')}
            activeOpacity={0.8}
          >
            <Text style={[styles.abaTexto, aba === 'proximos' ? styles.abaTextoAtivo : null]}>
              Próximos
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[styles.aba, aba === 'avaliar' ? styles.abaAtiva : null]}
            onPress={() => setAba('avaliar')}
            activeOpacity={0.8}
          >
            <Text style={[styles.abaTexto, aba === 'avaliar' ? styles.abaTextoAtivo : null]}>
              Participei · Avaliar
            </Text>
          </TouchableOpacity>
        </View>

        {carregando ? (
          <ActivityIndicator size="large" color={COR.verde} style={{ marginTop: 30 }} />
        ) : erro ? (
          <Text style={styles.vazio}>{erro}</Text>
        ) : null}

        {!carregando && !erro && aba === 'proximos' && proximos.length === 0 ? (
          <Text style={styles.vazio}>
            Você ainda não confirmou presença em nenhum evento. Confirme na tela de Eventos.
          </Text>
        ) : null}

        {!carregando && !erro && aba === 'proximos' &&
          proximos.map((evento) => (
            <CartaoEvento
              key={evento.idEvento}
              evento={evento}
              botao={
                <TouchableOpacity
                  style={[styles.botao, styles.botaoContorno]}
                  onPress={() => cancelarPresenca(evento)}
                  activeOpacity={0.85}
                >
                  <Text style={styles.botaoContornoTexto}>Cancelar presença</Text>
                </TouchableOpacity>
              }
            />
          ))}

        {!carregando && !erro && aba === 'avaliar' && participei.length === 0 ? (
          <Text style={styles.vazio}>
            Quando um evento em que você confirmou presença for concluído, ele aparece aqui
            para você avaliar.
          </Text>
        ) : null}

        {!carregando && !erro && aba === 'avaliar' &&
          participei.map((evento) => {
            const nota = minhasNotas[evento.idEvento];
            const jaAvaliou = nota !== undefined && nota !== null;
            return (
              <CartaoEvento
                key={evento.idEvento}
                evento={evento}
                botao={
                  <TouchableOpacity
                    style={jaAvaliou ? [styles.botao, styles.botaoContorno] : styles.botao}
                  onPress={() => navigation.navigate('Evento', { aba: 'avaliar', idEvento: evento.idEvento })}
                    activeOpacity={0.85}
                  >
                    <Text style={jaAvaliou ? styles.botaoContornoTexto : styles.botaoTexto}>
                      {jaAvaliou ? 'Avaliado · nota ' + nota : 'Avaliar evento'}
                    </Text>
                  </TouchableOpacity>
                }
              />
            );
          })}
      </ScrollView>

      <BottomTabBar active="perfil" navigation={navigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  tela: {
    flex: 1,
    backgroundColor: COR.fundo,
  },
  conteudo: {
    padding: 16,
    paddingBottom: 40,
  },
  titulo: {
    fontSize: 28,
    fontWeight: '800',
    color: COR.texto,
    marginTop: 8,
  },
  subtitulo: {
    fontSize: 15,
    color: COR.textoSuave,
    marginTop: 2,
  },
  atualizar: {
    fontSize: 14,
    fontWeight: '700',
    color: COR.verde,
    marginTop: 8,
    marginBottom: 14,
  },

  // abas
  trilho: {
    flexDirection: 'row',
    backgroundColor: COR.caixa,
    borderRadius: 24,
    padding: 5,
    marginBottom: 18,
  },
  aba: {
    flex: 1,
    height: 40,
    borderRadius: 20,
    alignItems: 'center',
    justifyContent: 'center',
  },
  abaAtiva: {
    backgroundColor: '#ffffff',
  },
  abaTexto: {
    fontSize: 14,
    fontWeight: '600',
    color: COR.textoSuave,
  },
  abaTextoAtivo: {
    color: COR.texto,
    fontWeight: '700',
  },

  // cards
  cardSombra: {
    backgroundColor: COR.card,
    borderRadius: 28,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.07,
    shadowRadius: 12,
    elevation: 3,
  },
  card: {
    borderRadius: 28,
    overflow: 'hidden',
    backgroundColor: COR.card,
  },
  faixa: {
    paddingHorizontal: 20,
    paddingVertical: 12,
  },
  faixaTexto: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '800',
    letterSpacing: 1,
  },
  corpo: {
    padding: 20,
  },
  nome: {
    fontSize: 20,
    fontWeight: '800',
    color: COR.texto,
  },
  instituicao: {
    fontSize: 14,
    fontWeight: '600',
    color: COR.verde,
    marginTop: 4,
  },
  linhaInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  linhaTexto: {
    fontSize: 15,
    color: COR.texto,
    marginLeft: 10,
    flex: 1,
  },
  descricao: {
    fontSize: 15,
    color: COR.textoSuave,
    lineHeight: 21,
    marginTop: 6,
  },
  vazio: {
    fontSize: 14,
    color: COR.textoSuave,
    textAlign: 'center',
    marginTop: 30,
  },

  // botões
  botao: {
    backgroundColor: COR.verde,
    borderColor: COR.verde,
    borderWidth: 2,
    borderRadius: 24,
    height: 48,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 18,
  },
  botaoTexto: {
    color: '#ffffff',
    fontSize: 15,
    fontWeight: '700',
  },
  botaoContorno: {
    backgroundColor: '#ffffff',
  },
  botaoContornoTexto: {
    color: COR.verde,
    fontSize: 15,
    fontWeight: '700',
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