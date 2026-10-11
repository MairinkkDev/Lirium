import React, { useState } from 'react';
import { SafeAreaView, ScrollView, View, Text, TextInput, Button } from 'react-native';
import axios from 'axios';
import { API_URL } from '../context/UsuarioContext';


export default function AvaliacaoEventoScreen({ navigation, route }) {
  const idEvento = route?.params?.idEvento;
  const nomeEvento = route?.params?.nomeEvento ?? 'Evento';

  const [nota, setNota] = useState(0);
  const [comentario, setComentario] = useState('');
  const [loading, setLoading] = useState(false);
  const [mensagem, setMensagem] = useState('');

  const handleEnviar = () => {
    if (!idEvento) {
      setMensagem('Evento não informado.');
      return;
    }
    if (nota === 0) {
      setMensagem('Escolha uma nota de 1 a 5.');
      return;
    }

    setLoading(true);
    setMensagem('');

    const dados = {
      idEvento: idEvento,
      nota: nota,
      comentario: comentario,
    };

    axios
      .post(`${API_URL}/evento-avaliacoes`, dados)
      .then((response) => {
        setMensagem('Avaliação enviada com sucesso.');
      })
      .catch((err) => {
        console.error('Erro:', err.message);
        console.error('Status:', err.response?.status);
        console.error('Resposta:', JSON.stringify(err.response?.data, null, 2));

        if (err.response?.status === 401) {
          setMensagem('Sessão expirada. Faça login novamente.');
        } else if (err.response?.status === 422) {
          const erros = err.response.data?.errors;
          const primeira = erros ? Object.values(erros)[0][0] : null;
          setMensagem(primeira || 'Verifique os dados informados.');
        } else if (err.response) {
          setMensagem(`Erro do servidor (${err.response.status}).`);
        } else if (err.code === 'ERR_NETWORK') {
          setMensagem('Sem conexão com o servidor.');
        } else {
          setMensagem(`Erro inesperado: ${err.message}`);
        }
      })
      .finally(() => {
        setLoading(false);
      });
  };

  return (
    <SafeAreaView style={{ flex: 1 }}>
      <ScrollView keyboardShouldPersistTaps="handled">
        <Text>Avaliar evento</Text>
        <Text>{nomeEvento}</Text>

        <Text>Nota: {nota === 0 ? 'nenhuma' : nota}</Text>
        <View style={{ flexDirection: 'row' }}>
          {[1, 2, 3, 4, 5].map((n) => (
            <Button
              key={n}
              title={nota === n ? `[${n}]` : String(n)}
              onPress={() => setNota(n)}
            />
          ))}
        </View>

        <Text>Comentário (opcional):</Text>
        <TextInput
          placeholder="Escreva sua opinião sobre o evento"
          value={comentario}
          onChangeText={setComentario}
          multiline
        />

        {mensagem ? <Text>{mensagem}</Text> : null}

        <Button
          title={loading ? 'Enviando...' : 'Enviar avaliação'}
          onPress={handleEnviar}
          disabled={loading}
        />
        <Button title="Voltar" onPress={() => navigation.goBack()} />
      </ScrollView>
    </SafeAreaView>
  );
}