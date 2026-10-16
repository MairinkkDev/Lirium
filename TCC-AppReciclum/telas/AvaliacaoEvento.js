import React, { useState, useEffect } from 'react';
import {
  SafeAreaView,
  ScrollView,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  Modal,
  Alert,
  StyleSheet,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import axios from 'axios';
import { API_URL, useUsuario } from '../context/UsuarioContext';
import BottomTabBar from './components/BottomTabBar';


const COR = {
  fundo: '#f5f4ea',
  card: '#ffffff',
  caixa: '#edf1e4',
  verde: '#0a7d3e',
  verdeClaro: '#5aab4f',
  texto: '#13261a',
  textoSuave: '#6b7468',
  linha: '#e4e6dc',
  estrelaVazia: '#b9bdb2',
  perigo: '#b3261e',
};

// os motivos que aparecem na denúncia
const MOTIVOS = [
  { id: 'ofensivo', icone: 'flame-outline', titulo: 'Ofensivo ou agressivo', desc: 'Xingamentos, ataques ou discurso de ódio' },
  { id: 'spam', icone: 'megaphone-outline', titulo: 'Spam ou propaganda', desc: 'Links, divulgação ou texto repetido' },
  { id: 'falso', icone: 'alert-circle-outline', titulo: 'Informação falsa', desc: 'Conta algo que não aconteceu no evento' },
  { id: 'outro', icone: 'ellipsis-horizontal-circle-outline', titulo: 'Outro motivo', desc: 'Algo que não está nas opções acima' },
];



// "2026-10-21 09:00:00" vira "21/10/2026"
function formatarData(texto) {
  if (!texto) {
    return '';
  }
  return texto.substring(8, 10) + '/' + texto.substring(5, 7) + '/' + texto.substring(0, 4);
}

// "2026-10-21 09:00:00" vira "09:00"
function formatarHora(texto) {
  if (!texto) {
    return '';
  }
  return texto.substring(11, 16);
}

// pega o nome da primeira instituição do evento
function nomeDaInstituicao(evento) {
  if (evento.instituicoes && evento.instituicoes.length > 0) {
    return evento.instituicoes[0].nomeInstituicao;
  }
  return '';
}

// Pega o ID da avaliação, verificando qual campo está disponível.
function pegarId(avaliacao) {
  return avaliacao.idEventoAvaliacao || avaliacao.idAvaliacao || avaliacao.id;
}
function pegarNota(avaliacao) {
  return Number(avaliacao.notaAvaliacao || avaliacao.nota || 0);
}
function pegarTexto(avaliacao) {
  return avaliacao.descricaoAvaliacao || avaliacao.comentario || '';
}

//mostrar as 5 estrelas
function desenharEstrelas(nota, tamanho) {
  return (
    <View style={{ flexDirection: 'row' }}>
      {[1, 2, 3, 4, 5].map((n) => (
        <Ionicons
          key={n}
          name={n <= nota ? 'star' : 'star-outline'}
          size={tamanho}
          color={n <= nota ? COR.verde : COR.estrelaVazia}
          style={{ marginRight: 4 }}
        />
      ))}
    </View>
  );
}

// estrelas funcionando
function escolherNota(notaAtual, mudarNota, tamanho) {
  return (
    <View style={{ flexDirection: 'row' }}>
      {[1, 2, 3, 4, 5].map((n) => (
        <TouchableOpacity key={n} onPress={() => mudarNota(n)}>
          <Ionicons
            name={n <= notaAtual ? 'star' : 'star-outline'}
            size={tamanho}
            color={n <= notaAtual ? COR.verde : COR.estrelaVazia}
            style={{ marginRight: 4 }}
          />
        </TouchableOpacity>
      ))}
    </View>
  );
}

// parte de avaliação 

function CartaoAvaliacao({ evento, usuario }) {
  const idEvento = evento.idEvento;

  const [avaliacoes, setAvaliacoes] = useState([]);
  const [carregandoLista, setCarregandoLista] = useState(true);

  // formulário de nova avaliação
  const [nota, setNota] = useState(0);
  const [comentario, setComentario] = useState('');
  const [mensagem, setMensagem] = useState('');
  const [enviando, setEnviando] = useState(false);

  // edição de um comentário
  const [editandoId, setEditandoId] = useState(null);
  const [notaEdicao, setNotaEdicao] = useState(0);
  const [textoEdicao, setTextoEdicao] = useState('');
  const [erroEdicao, setErroEdicao] = useState('');
  const [salvando, setSalvando] = useState(false);

  // painel que aparece ao cliclar nos três pontinhos
  const [painel, setPainel] = useState(null);
  const [avaliacaoSelecionada, setAvaliacaoSelecionada] = useState(null);

  // denúncia
  const [motivo, setMotivo] = useState(null);
  const [detalhes, setDetalhes] = useState('');
  const [erroDenuncia, setErroDenuncia] = useState('');
  const [enviandoDenuncia, setEnviandoDenuncia] = useState(false);

  //buscar as avaliações do evento 

function buscarAvaliacoes() {
  setCarregandoLista(true);

  axios
    .get(API_URL + '/evento-avaliacoes', {
      params: { idEvento: idEvento }
    })
    .then((response) => {
      let lista = response.data;

      // Verifica se os dados vieram diretamente como uma lista
      if (!Array.isArray(lista)) {
        lista = lista.data;
      }

      setAvaliacoes(
        lista.filter((a) => Number(a.idEvento) === idEvento)
      );
    })
    .catch((err) => {
      console.log('Erro ao buscar avaliações:', err.message);
    })
    .finally(() => {
      setCarregandoLista(false);
    });
}

//busca das avaliações quando o componente aparece pela primeira vez.
useEffect(() => {
  buscarAvaliacoes();
}, []);

// Verifica se a avaliação pertence ao usuário que está conectado.
function ehMeu(avaliacao) {
  if (!usuario) {
    return false;
  }

  return avaliacao.idUsuario === usuario.idUsuario;
}

  // enviar, editar e apagar 

  function enviarAvaliacao() {
    if (nota === 0) {
      setMensagem('Escolha uma nota de 1 a 5.');
      return;
    }

    setEnviando(true);
    setMensagem('');

    axios
      .post(API_URL + '/evento-avaliacoes', {
        idEvento: idEvento,
        notaAvaliacao: nota,
        descricaoAvaliacao: comentario,
        nota: nota,
        comentario: comentario,
      })
      .then(() => {
        setNota(0);
        setComentario('');
        buscarAvaliacoes();
      })
      .catch((err) => {
        console.log('Erro ao enviar avaliação:', err.response ? err.response.data : err.message);
        setMensagem('Não foi possível enviar a avaliação.');
      })
      .finally(() => {
        setEnviando(false);
      });
  }

  function comecarEdicao(avaliacao) {
    setEditandoId(pegarId(avaliacao));
    setNotaEdicao(pegarNota(avaliacao));
    setTextoEdicao(pegarTexto(avaliacao));
    setErroEdicao('');
  }

  function salvarEdicao() {
    if (notaEdicao === 0) {
      setErroEdicao('Escolha uma nota de 1 a 5.');
      return;
    }

    setSalvando(true);
    setErroEdicao('');

    axios
      .put(API_URL + '/evento-avaliacoes/' + editandoId, {
        idEvento: idEvento,
        notaAvaliacao: notaEdicao,
        descricaoAvaliacao: textoEdicao,
        nota: notaEdicao,
        comentario: textoEdicao,
      })
      .then(() => {
        setEditandoId(null);
        buscarAvaliacoes();
      })
      .catch((err) => {
        console.log('Erro ao editar:', err.response ? err.response.data : err.message);
        setErroEdicao('Não foi possível salvar. Tente novamente.');
      })
      .finally(() => {
        setSalvando(false);
      });
  }

  function apagarComentario(avaliacao) {
    axios
      .delete(API_URL + '/evento-avaliacoes/' + pegarId(avaliacao))
      .then(() => {
        setAvaliacoes(avaliacoes.filter((x) => pegarId(x) !== pegarId(avaliacao)));
      })
      .catch((err) => {
        console.log('Erro ao apagar:', err.response ? err.response.data : err.message);
        Alert.alert('Erro', 'Não foi possível apagar o comentário.');
      });
  }

  function confirmarApagar(avaliacao) {
    Alert.alert('Apagar comentário', 'Quer apagar a sua avaliação?', [
      { text: 'Cancelar', style: 'cancel' },
      { text: 'Apagar', style: 'destructive', onPress: () => apagarComentario(avaliacao) },
    ]);
  }

  // painel que sobe de baixo 

  // toque nos três pontinhos
  function abrirMenu(avaliacao) {
    setAvaliacaoSelecionada(avaliacao);
    setPainel('menu');
  }

  function fecharPainel() {
    if (enviandoDenuncia) {
      return; // não deixa fechar no meio do envio
    }
    setPainel(null);
  }

  function escolherEditar() {
    setPainel(null);
    comecarEdicao(avaliacaoSelecionada);
  }

  function escolherApagar() {
    const avaliacao = avaliacaoSelecionada;
    setPainel(null);
 
    setTimeout(() => {
      confirmarApagar(avaliacao);
    }, 250);
  }

  function escolherDenunciar() {
    setMotivo(null);
    setDetalhes('');
    setErroDenuncia('');
    setPainel('denuncia');
  }

  function enviarDenuncia() {
    if (motivo === null) {
      setErroDenuncia('Escolha um motivo para continuar.');
      return;
    }

    setEnviandoDenuncia(true);
    setErroDenuncia('');

    axios
      .post(API_URL + '/evento-avaliacoes/' + pegarId(avaliacaoSelecionada) + '/denunciar', {
        motivo: motivo,
        detalhes: detalhes.trim(),
      })
      .then(() => {
        setPainel('sucesso');
      })
      .catch((err) => {
        console.log('Erro ao denunciar:', err.response ? err.response.data : err.message);
        setErroDenuncia('Não foi possível enviar. Tente novamente.');
      })
      .finally(() => {
        setEnviandoDenuncia(false);
      });
  }


  // formulário de nova avaliação (ou aviso de que já avaliou)
  function desenharFormulario() {
    if (carregandoLista) {
      return null; u
    }

    const minhas = avaliacoes.filter((a) => ehMeu(a));

    if (minhas.length > 0) {
      return (
        <View style={styles.caixa}>
          <Text style={styles.caixaTitulo}>Você já avaliou este evento. Obrigado!</Text>
        </View>
      );
    }

    return (
      <View style={styles.caixa}>
        <Text style={styles.caixaTitulo}>Como foi sua experiência?</Text>

        <View style={{ marginTop: 10, marginBottom: 14 }}>{escolherNota(nota, setNota, 26)}</View>

        <TextInput
          style={styles.input}
          placeholder="Conte como foi o evento..."
          placeholderTextColor={COR.textoSuave}
          value={comentario}
          onChangeText={setComentario}
          multiline
          textAlignVertical="top"
        />

        {mensagem ? <Text style={styles.mensagem}>{mensagem}</Text> : null}

        {/* [estiloBase, estiloExtra]: junta dois estilos. O botão fica meio apagado sem nota */}
        <TouchableOpacity
          style={[styles.botao, nota === 0 ? styles.opaco : null]}
          onPress={enviarAvaliacao}
          disabled={enviando}
          activeOpacity={0.85}
        >
          {enviando ? (
            <ActivityIndicator color="#ffffff" />
          ) : (
            <Text style={styles.botaoTexto}>Enviar avaliação (+30 pontos)</Text>
          )}
        </TouchableOpacity>
      </View>
    );
  }

  function desenharComentario(avaliacao) {
    const id = pegarId(avaliacao);
    const estaEditando = editandoId === id;

    return (
      <View key={id} style={styles.avaliacao}>
        <View style={styles.avaliacaoTopo}>
          <View style={{ flex: 1 }}>
            <Text style={styles.autor}>
              {avaliacao.usuario ? avaliacao.usuario.nomeUsuario : 'Participante'}
            </Text>
            <Text style={styles.tempo}>{formatarData(avaliacao.created_at)}</Text>
          </View>

          {/* os três pontinhos somem enquanto edita */}
          {estaEditando ? null : (
            <TouchableOpacity
              style={styles.menuBotao}
              onPress={() => abrirMenu(avaliacao)}
              hitSlop={{ top: 10, bottom: 10, left: 10, right: 10 }}
            >
              <Ionicons name="ellipsis-vertical" size={20} color={COR.textoSuave} />
            </TouchableOpacity>
          )}
        </View>

        {estaEditando ? (
          <View style={{ marginTop: 10 }}>
            {escolherNota(notaEdicao, setNotaEdicao, 24)}

            <TextInput
              style={[styles.input, styles.inputEdicao]}
              value={textoEdicao}
              onChangeText={setTextoEdicao}
              placeholder="Edite seu comentário..."
              placeholderTextColor={COR.textoSuave}
              multiline
              textAlignVertical="top"
            />

            {erroEdicao ? <Text style={styles.mensagemErro}>{erroEdicao}</Text> : null}

            <View style={styles.edicaoBotoes}>
              <TouchableOpacity
                style={[styles.botao, styles.botaoContorno, styles.botaoPequeno]}
                onPress={() => setEditandoId(null)}
                activeOpacity={0.85}
              >
                <Text style={styles.botaoContornoTexto}>Cancelar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.botao, styles.botaoPequeno]}
                onPress={salvarEdicao}
                disabled={salvando}
                activeOpacity={0.85}
              >
                {salvando ? (
                  <ActivityIndicator color="#ffffff" />
                ) : (
                  <Text style={styles.botaoTexto}>Salvar</Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        ) : (
          <View>
            <View style={{ marginVertical: 6 }}>{desenharEstrelas(pegarNota(avaliacao), 14)}</View>
            {pegarTexto(avaliacao) ? (
              <Text style={styles.comentario}>{pegarTexto(avaliacao)}</Text>
            ) : null}
          </View>
        )}
      </View>
    );
  }

  // média da nota

  let soma = 0;
  avaliacoes.forEach((a) => {
    soma = soma + pegarNota(a);
  });
  const media = avaliacoes.length > 0 ? (soma / avaliacoes.length).toFixed(1) : '–';
  const textoQtd = avaliacoes.length === 1 ? '1 avaliação' : avaliacoes.length + ' avaliações';

  // o que aparece no card

  return (
    <View style={[styles.cardSombra, { padding: 22 }]}>
      <View style={styles.cardTopo}>
        <View style={{ flex: 1, paddingRight: 12 }}>
          <Text style={styles.sobretitulo}>{formatarData(evento.dataInicioEvento)}</Text>
          <Text style={styles.nome}>{evento.nomeEvento}</Text>
          <Text style={styles.instituicao}>{nomeDaInstituicao(evento)}</Text>
        </View>

        <View style={{ alignItems: 'flex-end' }}>
          <Text style={styles.media}>{media}</Text>
          <Text style={styles.qtd}>{textoQtd}</Text>
        </View>
      </View>

      {desenharFormulario()}

      {carregandoLista ? (
        <ActivityIndicator color={COR.verde} style={{ marginTop: 20 }} />
      ) : null}

      {!carregandoLista && avaliacoes.length === 0 ? (
        <Text style={styles.vazio}>Ainda não há avaliações. Seja a primeira pessoa a avaliar.</Text>
      ) : null}

      {avaliacoes.map((a) => desenharComentario(a))}

      {/* PAINEL */}
      <Modal visible={painel !== null} transparent animationType="slide" onRequestClose={fecharPainel}>
        <View style={styles.fundoModal}>

          {/* tocar na parte escura fecha o painel */}
          <TouchableOpacity style={{ flex: 1 }} activeOpacity={1} onPress={fecharPainel} />

          <View style={styles.folha}>
            <View style={styles.folhaAlca} />

            {/* menu dos três pontinhos */}
            {painel === 'menu' && avaliacaoSelecionada ? (
              <View>
                {ehMeu(avaliacaoSelecionada) ? (
                  <View>
                    <TouchableOpacity style={styles.folhaItem} onPress={escolherEditar}>
                      <Ionicons name="create-outline" size={22} color={COR.texto} />
                      <Text style={styles.folhaTexto}>Editar comentário</Text>
                    </TouchableOpacity>

                    <TouchableOpacity style={styles.folhaItem} onPress={escolherApagar}>
                      <Ionicons name="trash-outline" size={22} color={COR.perigo} />
                      <Text style={[styles.folhaTexto, { color: COR.perigo }]}>Apagar comentário</Text>
                    </TouchableOpacity>
                  </View>
                ) : (
                  <TouchableOpacity style={styles.folhaItem} onPress={escolherDenunciar}>
                    <Ionicons name="flag-outline" size={22} color={COR.texto} />
                    <Text style={styles.folhaTexto}>Denunciar comentário</Text>
                  </TouchableOpacity>
                )}

                <TouchableOpacity style={[styles.folhaItem, styles.folhaCancelar]} onPress={fecharPainel}>
                  <Text style={styles.folhaCancelarTexto}>Cancelar</Text>
                </TouchableOpacity>
              </View>
            ) : null}

            {/* formulário de denúncia */}
            {painel === 'denuncia' && avaliacaoSelecionada ? (
              <ScrollView showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled">
                <View style={styles.denunciaCabecalho}>
                  <View style={styles.denunciaBadge}>
                    <Ionicons name="flag" size={22} color={COR.perigo} />
                  </View>
                  <View style={{ flex: 1 }}>
                    <Text style={styles.denunciaTitulo}>Denunciar comentário</Text>
                    <Text style={styles.denunciaSub}>Sua denúncia é anônima para quem escreveu.</Text>
                  </View>
                </View>

                {/* o comentário denunciado, para ter certeza de que é esse */}
                <View style={styles.denunciaPrevia}>
                  <Text style={styles.autor}>
                    {avaliacaoSelecionada.usuario ? avaliacaoSelecionada.usuario.nomeUsuario : 'Participante'}
                  </Text>
                  <Text style={styles.denunciaPreviaTexto} numberOfLines={3}>
                    {pegarTexto(avaliacaoSelecionada) || 'Avaliação sem comentário.'}
                  </Text>
                </View>

                <Text style={styles.denunciaPergunta}>Qual é o problema?</Text>

                {MOTIVOS.map((m) => {
                  const ativo = motivo === m.id;

                  return (
                    <TouchableOpacity
                      key={m.id}
                      style={[styles.motivo, ativo ? styles.motivoAtivo : null]}
                      onPress={() => {
                        setMotivo(m.id);
                        setErroDenuncia('');
                      }}
                      disabled={enviandoDenuncia}
                      activeOpacity={0.8}
                    >
                      <View style={[styles.motivoIcone, ativo ? { backgroundColor: COR.perigo } : null]}>
                        <Ionicons name={m.icone} size={20} color={ativo ? '#ffffff' : COR.textoSuave} />
                      </View>

                      <View style={{ flex: 1 }}>
                        <Text style={styles.motivoTitulo}>{m.titulo}</Text>
                        <Text style={styles.motivoDesc}>{m.desc}</Text>
                      </View>

                      <View style={[styles.radio, ativo ? { borderColor: COR.perigo } : null]}>
                        {ativo ? <View style={styles.radioMiolo} /> : null}
                      </View>
                    </TouchableOpacity>
                  );
                })}

                <TextInput
                  style={[styles.input, styles.inputDenuncia]}
                  placeholder="Quer explicar melhor? (opcional)"
                  placeholderTextColor={COR.textoSuave}
                  value={detalhes}
                  onChangeText={setDetalhes}
                  maxLength={150}
                  multiline
                  textAlignVertical="top"
                  editable={!enviandoDenuncia}
                />
                <Text style={styles.contador}>{detalhes.length}/150</Text>

                {erroDenuncia ? <Text style={styles.mensagemErro}>{erroDenuncia}</Text> : null}

                <TouchableOpacity
                  style={[styles.botao, styles.botaoPerigo, motivo === null ? styles.opaco : null]}
                  onPress={enviarDenuncia}
                  disabled={enviandoDenuncia}
                  activeOpacity={0.85}
                >
                  {enviandoDenuncia ? (
                    <ActivityIndicator color="#ffffff" />
                  ) : (
                    <Text style={styles.botaoTexto}>Enviar denúncia</Text>
                  )}
                </TouchableOpacity>

                <TouchableOpacity style={{ alignItems: 'center', paddingVertical: 14 }} onPress={fecharPainel}>
                  <Text style={styles.cancelarTexto}>Cancelar</Text>
                </TouchableOpacity>
              </ScrollView>
            ) : null}

            {/* denúncia enviada */}
            {painel === 'sucesso' ? (
              <View style={styles.sucesso}>
                <View style={styles.sucessoIcone}>
                  <Ionicons name="checkmark" size={40} color="#ffffff" />
                </View>
                <Text style={styles.sucessoTitulo}>Denúncia enviada</Text>
                <Text style={styles.sucessoTexto}>
                  Obrigado por avisar! Nossa equipe vai analisar o comentário.
                </Text>
                <TouchableOpacity
                  style={[styles.botao, { alignSelf: 'stretch' }]}
                  onPress={fecharPainel}
                  activeOpacity={0.85}
                >
                  <Text style={styles.botaoTexto}>Fechar</Text>
                </TouchableOpacity>
              </View>
            ) : null}
          </View>
        </View>
      </Modal>
    </View>
  );
}



export default function EventosScreen({ navigation }) {
  const contexto = useUsuario();
  const usuario = contexto.usuario;

 
  const [aba, setAba] = useState('proximos'); 
  const [eventos, setEventos] = useState([]); 
  const [confirmados, setConfirmados] = useState([]); 
  const [carregando, setCarregando] = useState(true);
  const [erro, setErro] = useState('');

  /* buscar dados na API */

  function carregarEventos() {
    setCarregando(true);
    setErro('');

    axios
      .get(API_URL + '/eventos')
      .then((response) => {
        setEventos(response.data);
      })
      .catch((err) => {
        console.log('Erro ao buscar eventos:', err.message);
        setErro('Não foi possível carregar os eventos.');
      })
      .finally(() => {
        setCarregando(false);
      });

    axios
      .get(API_URL + '/minhas-participacoes')
      .then((response) => {
        setConfirmados(response.data);
      })
      .catch((err) => {
        console.log('Erro ao buscar participações:', err.message);
      });
  }

  useEffect(() => {
    carregarEventos();
  }, []);

  /* presença */

  function mudarPresenca(evento) {
    const id = evento.idEvento;

    if (confirmados.includes(id)) {
      // já tinha confirmado: cancela
      axios
        .delete(API_URL + '/eventos/' + id + '/participar')
        .then(() => {
          setConfirmados(confirmados.filter((x) => x !== id));
        })
        .catch((err) => {
          console.log('Erro ao cancelar presença:', err.response ? err.response.data : err.message);
          Alert.alert('Erro', 'Não foi possível cancelar a presença.');
        });
    } else {
      // ainda não tinha: confirma
      axios
        .post(API_URL + '/eventos/' + id + '/participar')
        .then(() => {
          setConfirmados(confirmados.concat([id]));
        })
        .catch((err) => {
          console.log('Erro ao confirmar presença:', err.response ? err.response.data : err.message);
          Alert.alert('Erro', 'Não foi possível confirmar a presença.');
        });
    }
  }

  /* listas de cada aba  */

  const proximos = eventos.filter(
    (e) => e.statusEvento === 'agendado' || e.statusEvento === 'em_andamento'
  );

  const participei = eventos.filter(
    (e) => e.statusEvento === 'concluido' && confirmados.includes(e.idEvento)
  );

  // o que aparece na tela

  return (
    <SafeAreaView style={styles.tela}>
      <ScrollView contentContainerStyle={styles.conteudo} keyboardShouldPersistTaps="handled">
        <Text style={styles.titulo}>Eventos</Text>
        <Text style={styles.subtitulo}>Ações ambientais perto de você</Text>

        <TouchableOpacity onPress={carregarEventos}>
          <Text style={styles.atualizar}>Atualizar lista</Text>
        </TouchableOpacity>

        {/* as duas abas */}
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
          <ActivityIndicator size="large" color={COR.verde} style={{ marginTop: 40 }} />
        ) : null}
        {erro ? <Text style={styles.erro}>{erro}</Text> : null}

        {/* ABA PRÓXIMOS */}
        {aba === 'proximos' && !carregando && proximos.length === 0 ? (
          <Text style={styles.vazio}>Nenhum evento agendado por enquanto.</Text>
        ) : null}

        {aba === 'proximos' &&
          proximos.map((evento) => {
            const confirmado = confirmados.includes(evento.idEvento);

            return (
              <View key={evento.idEvento} style={styles.cardSombra}>
                <View style={styles.card}>
                  <LinearGradient
                    colors={[COR.verde, COR.verdeClaro]}
                    start={{ x: 0, y: 0 }}
                    end={{ x: 1, y: 0 }}
                    style={styles.faixa}
                  >
                    <Text style={styles.faixaTexto}>
                      {evento.statusEvento.replace('_', ' ').toUpperCase()}
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
                        <Text style={styles.linhaTexto}>
                          {formatarHora(evento.dataInicioEvento)} – {formatarHora(evento.dataFimEvento)}
                        </Text>
                      </View>
                      <View style={styles.linhaInfo}>
                        <Ionicons name="location-outline" size={18} color={COR.textoSuave} />
                        <Text style={styles.linhaTexto}>
                          {evento.logradouroEnderecoEvento}, {evento.bairroEnderecoEvento}
                        </Text>
                      </View>
                    </View>

                    <Text style={styles.descricao}>{evento.descricaoEvento}</Text>

                    {/* verde = confirmou e Branco = já confirmou */}
                    <TouchableOpacity
                      style={confirmado ? [styles.botao, styles.botaoContorno] : styles.botao}
                      onPress={() => mudarPresenca(evento)}
                      activeOpacity={0.85}
                    >
                      <Text style={confirmado ? styles.botaoContornoTexto : styles.botaoTexto}>
                        {confirmado ? 'Presença confirmada ✓' : 'Confirmar presença'}
                      </Text>
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            );
          })}

        {/* ABA PARTICIPEI · AVALIAR */}
        {aba === 'avaliar' && !carregando && participei.length === 0 ? (
          <Text style={styles.vazio}>
            Quando um evento em que você confirmou presença for concluído, ele aparece aqui
            para você avaliar.
          </Text>
        ) : null}

        {aba === 'avaliar' &&
          participei.map((evento) => (
            <CartaoAvaliacao key={evento.idEvento} evento={evento} usuario={usuario} />
          ))}
      </ScrollView>

      <BottomTabBar active="inicio" navigation={navigation} />
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
  cardTopo: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 18,
  },
  sobretitulo: {
    fontSize: 11,
    fontWeight: '700',
    letterSpacing: 1,
    color: COR.textoSuave,
    marginBottom: 6,
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
  media: {
    fontSize: 24,
    fontWeight: '800',
    color: COR.texto,
  },
  qtd: {
    fontSize: 12,
    color: COR.textoSuave,
    marginTop: 2,
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

  // botões:
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
  botaoPerigo: {
    backgroundColor: COR.perigo,
    borderColor: COR.perigo,
  },
  botaoPequeno: {
    height: 40,
    minWidth: 96,
    marginTop: 0,
    marginLeft: 10,
    borderRadius: 20,
  },
  opaco: {
    opacity: 0.5,
  },

  // formulário
  caixa: {
    backgroundColor: COR.caixa,
    borderRadius: 24,
    padding: 16,
  },
  caixaTitulo: {
    fontSize: 16,
    fontWeight: '700',
    color: COR.texto,
  },
  input: {
    backgroundColor: '#ffffff',
    borderRadius: 20,
    minHeight: 100,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 15,
    color: COR.texto,
  },
  inputEdicao: {
    marginTop: 12,
    backgroundColor: COR.caixa,
  },
  inputDenuncia: {
    backgroundColor: COR.caixa,
    minHeight: 80,
    marginTop: 6,
  },
  mensagem: {
    fontSize: 13,
    color: COR.textoSuave,
    marginTop: 10,
  },
  mensagemErro: {
    fontSize: 13,
    color: COR.perigo,
    textAlign: 'center',
    marginTop: 10,
  },

  // lista de comentários
  avaliacao: {
    borderTopWidth: 1,
    borderTopColor: COR.linha,
    marginTop: 18,
    paddingTop: 14,
  },
  avaliacaoTopo: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
  },
  autor: {
    fontSize: 14,
    fontWeight: '700',
    color: COR.texto,
  },
  tempo: {
    fontSize: 12,
    color: COR.textoSuave,
    marginTop: 2,
  },
  comentario: {
    fontSize: 15,
    color: COR.texto,
    lineHeight: 21,
    marginTop: 2,
  },
  menuBotao: {
    padding: 4,
    marginLeft: 8,
  },
  edicaoBotoes: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    marginTop: 12,
  },

  // painel que sobe de baixo
  fundoModal: {
    flex: 1,
    backgroundColor: 'rgba(19,38,26,0.45)',
    justifyContent: 'flex-end',
  },
  folha: {
    backgroundColor: '#ffffff',
    borderTopLeftRadius: 28,
    borderTopRightRadius: 28,
    paddingHorizontal: 20,
    paddingTop: 10,
    paddingBottom: 28,
    maxHeight: '88%',
  },
  folhaAlca: {
    alignSelf: 'center',
    width: 44,
    height: 5,
    borderRadius: 3,
    backgroundColor: COR.linha,
    marginBottom: 8,
  },
  folhaItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
  },
  folhaTexto: {
    fontSize: 16,
    fontWeight: '600',
    color: COR.texto,
    marginLeft: 14,
  },
  folhaCancelar: {
    borderTopWidth: 1,
    borderTopColor: COR.linha,
    marginTop: 4,
  },
  folhaCancelarTexto: {
    flex: 1,
    textAlign: 'center',
    fontSize: 16,
    fontWeight: '600',
    color: COR.textoSuave,
  },

  // denúncia
  denunciaCabecalho: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 6,
    marginBottom: 16,
  },
  denunciaBadge: {
    width: 46,
    height: 46,
    borderRadius: 23,
    backgroundColor: '#fbe9e7',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 14,
  },
  denunciaTitulo: {
    fontSize: 20,
    fontWeight: '800',
    color: COR.texto,
  },
  denunciaSub: {
    fontSize: 13,
    color: COR.textoSuave,
    marginTop: 2,
  },
  denunciaPrevia: {
    backgroundColor: COR.caixa,
    borderRadius: 18,
    padding: 14,
    borderLeftWidth: 4,
    borderLeftColor: COR.perigo,
    marginBottom: 18,
  },
  denunciaPreviaTexto: {
    fontSize: 14,
    color: COR.textoSuave,
    lineHeight: 20,
    marginTop: 4,
  },
  denunciaPergunta: {
    fontSize: 16,
    fontWeight: '700',
    color: COR.texto,
    marginBottom: 10,
  },
  motivo: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: COR.linha,
    borderRadius: 20,
    padding: 12,
    marginBottom: 10,
  },
  motivoAtivo: {
    borderColor: COR.perigo,
    backgroundColor: '#fdf3f2',
  },
  motivoIcone: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: COR.caixa,
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  motivoTitulo: {
    fontSize: 15,
    fontWeight: '700',
    color: COR.texto,
  },
  motivoDesc: {
    fontSize: 12,
    color: COR.textoSuave,
    marginTop: 2,
  },
  radio: {
    width: 22,
    height: 22,
    borderRadius: 11,
    borderWidth: 2,
    borderColor: COR.estrelaVazia,
    alignItems: 'center',
    justifyContent: 'center',
    marginLeft: 8,
  },
  radioMiolo: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: COR.perigo,
  },
  contador: {
    fontSize: 12,
    color: COR.textoSuave,
    textAlign: 'right',
    marginTop: 6,
  },
  cancelarTexto: {
    fontSize: 15,
    fontWeight: '600',
    color: COR.textoSuave,
  },

  // denúncia enviada
  sucesso: {
    alignItems: 'center',
    paddingTop: 16,
    paddingBottom: 8,
  },
  sucessoIcone: {
    width: 76,
    height: 76,
    borderRadius: 38,
    backgroundColor: COR.verde,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 16,
  },
  sucessoTitulo: {
    fontSize: 22,
    fontWeight: '800',
    color: COR.texto,
  },
  sucessoTexto: {
    fontSize: 15,
    color: COR.textoSuave,
    textAlign: 'center',
    lineHeight: 21,
    marginTop: 8,
    marginBottom: 6,
    paddingHorizontal: 8,
  },

  // avisos
  vazio: {
    fontSize: 14,
    color: COR.textoSuave,
    textAlign: 'center',
    marginTop: 18,
  },
  erro: {
    fontSize: 15,
    color: COR.perigo,
    textAlign: 'center',
    marginTop: 20,
  },
});