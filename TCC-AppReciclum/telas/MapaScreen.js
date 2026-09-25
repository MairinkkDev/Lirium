import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ActivityIndicator,
} from 'react-native';
import { WebView } from 'react-native-webview';
import * as Location from 'expo-location';

import AppHeader, {
  GREEN_DARK,
  GREEN_MAIN,
} from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';

// Mapa oficial de Ecopontos disponibilizado pela Prefeitura de São Paulo.
const ECOPONTOS_MAP_URL =
  'https://www.google.com/maps/d/embed?ehbc=2E312F&mid=1Kok7L5JyjXN758y3vy3kMsKYdJylrQE';

export default function MapaScreen({ navigation }) {
  const [carregando, setCarregando] = useState(true);
  const [erro, setErro] = useState(false);
  const [localizacao, setLocalizacao] = useState(null);

  useEffect(() => {
    obterLocalizacao();
  }, []);

  async function obterLocalizacao() {
    try {
      // Solicita permissão para acessar a localização
      const { status } =
        await Location.requestForegroundPermissionsAsync();

      if (status !== 'granted') {
        // Se o usuário negar, abre o mapa normalmente
        setLocalizacao(null);
        setCarregando(false);
        return;
      }

      // Obtém a localização atual
      const position =
        await Location.getCurrentPositionAsync({
          accuracy: Location.Accuracy.Balanced,
        });

      setLocalizacao({
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
      });

      setCarregando(false);
    } catch (error) {
      console.log('Erro ao obter localização:', error);

      // Mesmo se não conseguir localização,
      // o mapa continua funcionando.
      setLocalizacao(null);
      setCarregando(false);
    }
  }

  /*
   * O Google My Maps aceita os parâmetros de posição
   * pela URL.
   *
   * Quando conseguimos a localização:
   * https://www.google.com/maps/...&ll=LAT,LONG
   *
   * Assim o mapa abre centralizado onde a pessoa está.
   */
  const mapaUrl = localizacao
    ? `${ECOPONTOS_MAP_URL}&ll=${localizacao.latitude},${localizacao.longitude}&z=14`
    : ECOPONTOS_MAP_URL;

  return (
    <View style={styles.container}>
      <AppHeader />

      <View style={styles.tituloContainer}>
        <View>
          <Text style={styles.titulo}>Ecopontos</Text>
          <Text style={styles.subtitulo}>
            Pontos de descarte em São Paulo
          </Text>
        </View>
      </View>

      <View style={styles.mapContainer}>
        {carregando && (
          <View style={styles.loading}>
            <ActivityIndicator
              size="large"
              color={GREEN_MAIN}
            />

            <Text style={styles.loadingText}>
              Localizando você...
            </Text>
          </View>
        )}

        {erro ? (
          <View style={styles.erroContainer}>
            <Text style={styles.erroTitulo}>
              Não foi possível carregar o mapa.
            </Text>

            <Text style={styles.erroTexto}>
              Verifique sua conexão com a internet e tente novamente.
            </Text>
          </View>
        ) : !carregando ? (
          <WebView
            source={{ uri: mapaUrl }}
            style={styles.map}
            javaScriptEnabled
            domStorageEnabled
            startInLoadingState={false}
            originWhitelist={['*']}
            onError={() => {
              setErro(true);
            }}
          />
        ) : null}
      </View>

      <View style={styles.fonteContainer}>
        <Text style={styles.fonte}>
          Fonte: Prefeitura de São Paulo
        </Text>
      </View>

      <BottomTabBar
        active="telaMapa"
        navigation={navigation}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffff',
  },

  tituloContainer: {
    paddingHorizontal: 18,
    paddingVertical: 12,
    backgroundColor: '#ffffff',
    borderBottomWidth: 1,
    borderBottomColor: '#eeeeee',
  },

  titulo: {
    fontSize: 22,
    fontWeight: '700',
    color: GREEN_DARK,
  },

  subtitulo: {
    marginTop: 2,
    fontSize: 13,
    color: '#777777',
  },

  mapContainer: {
    flex: 1,
    overflow: 'hidden',
  },

  map: {
    flex: 1,
  },

  loading: {
    ...StyleSheet.absoluteFillObject,
    zIndex: 10,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#ffffff',
  },

  loadingText: {
    marginTop: 10,
    color: '#666666',
    fontSize: 14,
  },

  erroContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 35,
  },

  erroTitulo: {
    fontSize: 18,
    fontWeight: '700',
    color: '#333333',
    textAlign: 'center',
  },

  erroTexto: {
    marginTop: 8,
    fontSize: 14,
    color: '#777777',
    textAlign: 'center',
    lineHeight: 20,
  },

  fonteContainer: {
    paddingVertical: 5,
    paddingHorizontal: 12,
    backgroundColor: '#f7f7f7',
  },

  fonte: {
    fontSize: 10,
    color: '#777777',
    textAlign: 'center',
  },
});