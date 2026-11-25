import React, { useEffect, useState } from 'react';

import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Image,
  ScrollView,
  SafeAreaView,
  StatusBar,
  keyboardAvoidingView,
  Platform,
  ActivityIndicator,
} from 'react-native';
import { WebView } from 'react-native-webview';
import * as Location from 'expo-location';

import AppHeader from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';
import { Ionicons, MaterialCommunityIcons, } from '@expo/vector-icons';
import FontAwesome6 from '@expo/vector-icons/FontAwesome6';
import { LinearGradient } from 'expo-linear-gradient';
import FontAwesome from '@expo/vector-icons/FontAwesome';
import FontAwesome5 from '@expo/vector-icons/FontAwesome5';

// Mesmo mapa oficial de Ecopontos usado na tela MapaScreen
const ECOPONTOS_MAP_URL =
  'https://www.google.com/maps/d/embed?ehbc=2E312F&mid=1Kok7L5JyjXN758y3vy3kMsKYdJylrQE';

export default function telaMapaOngs({navigation}) {
  const [localizacao, setLocalizacao] = useState(null);
  const [carregandoMapa, setCarregandoMapa] = useState(true);

  // pega a localização do usuário para o preview já abrir centralizado nele
  useEffect(() => {
    async function obterLocalizacao() {
      try {
        const { status } = await Location.requestForegroundPermissionsAsync();
        if (status === 'granted') {
          const pos = await Location.getCurrentPositionAsync({
            accuracy: Location.Accuracy.Balanced,
          });
          setLocalizacao({
            latitude: pos.coords.latitude,
            longitude: pos.coords.longitude,
          });
        }
      } catch (error) {
        console.log('Erro ao obter localização:', error);
      } finally {
        setCarregandoMapa(false);
      }
    }
    obterLocalizacao();
  }, []);

  const mapaUrl = localizacao
    ? `${ECOPONTOS_MAP_URL}&ll=${localizacao.latitude},${localizacao.longitude}&z=14`
    : ECOPONTOS_MAP_URL;

  return (
    <View style={styles.container}>
       <AppHeader></AppHeader>  

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>

    {/*----------------------- FILTRO ---------------*/}
        <View style={styles.filtroContainer}>

        <TouchableOpacity  style={styles.OpaciFiltro}>

            <View style={styles.Botaofiltro}>

              <Text style={styles.TextoFiltro}>Todas</Text>
        
            </View>
        </TouchableOpacity>
        
        <TouchableOpacity  style={styles.OpaciFiltro}>
            <View style={styles.Botaofiltro}>

              <Text style={styles.TextoFiltro}>Ecoponto</Text>
        
            </View>
        </TouchableOpacity> 
      
         <TouchableOpacity  style={styles.OpaciFiltro}>
            <View style={styles.Botaofiltro}>

              <Text style={styles.TextoFiltro}>Cooperativa</Text>
        
            </View>
         </TouchableOpacity>

          <TouchableOpacity  style={styles.OpaciFiltro}>
            <View style={styles.Botaofiltro}>

              <Text style={styles.TextoFiltro} >Eventos</Text>
        
            </View>
          </TouchableOpacity>

          <TouchableOpacity  style={styles.OpaciFiltro}>
            <View style={styles.Botaofiltro}>

              <Text style={styles.TextoFiltro}>Ongs</Text>
        
            </View>

        </TouchableOpacity>
    
    </View>

     {/*-----------------------MAPA ---------------*/}
    
   <TouchableOpacity
    onPress={() => navigation.navigate('Mapa')}
    activeOpacity={0.9}
    style={styles.mapaPreview}
  >
    {carregandoMapa ? (
      <View style={styles.mapaLoading}>
        <ActivityIndicator size="large" color="#3fa63f" />
      </View>
    ) : (
      <WebView
        source={{ uri: mapaUrl }}
        style={{ flex: 1 }}
        scrollEnabled={false}
        javaScriptEnabled
        domStorageEnabled
        originWhitelist={['*']}
      />
    )}

    {/* camada transparente: o WebView não "rouba" o toque/rolagem e o card inteiro abre o mapa */}
    <View style={StyleSheet.absoluteFill} />

    <View style={styles.mapaExpandir}>
      <Ionicons name="expand-outline" size={18} color="#0f5c2e" />
    </View>
  </TouchableOpacity>
        
        
     {/*
      
      <View style={styles.agenciasContainer}>

      <TouchableOpacity style={styles.agenciaItem} >

        <View style={styles.agenciaIcon}>
          <FontAwesome name="recycle" size={24} color="white" />
        </View>

        <View style={styles.agenciaTexts}>
          <Text style={styles.agenciaName}>Ecoponto Central</Text>
          <Text style={styles.agencialocal}>Rua das Flores, N° 123</Text>
        </View>

      </TouchableOpacity>

      <TouchableOpacity style={styles.agenciaItem} >

        <View style={styles.agenciaIcon}>
          <FontAwesome name="recycle" size={24} color="white" />
        </View>

        <View style={styles.agenciaTexts}>
          <Text style={styles.agenciaName}>Ecoponto Santos</Text>
          <Text style={styles.agencialocal}>Rua dos almirantes, N° 13</Text>
        </View>

      </TouchableOpacity>

        <TouchableOpacity style={styles.agenciaItem} >

        <View style={styles.agenciaIcon}>
         <FontAwesome5 name="hand-holding-heart" size={24} color="white" />
        </View>

        <View style={styles.agenciaTexts}>
          <Text style={styles.agenciaName}>Raiz urbana</Text>
          <Text style={styles.agencialocal}>Rua do ipê, N° 67</Text>
        </View>

      </TouchableOpacity>

       <TouchableOpacity style={styles.agenciaItem} >

        <View style={styles.agenciaIcon}>
        <MaterialCommunityIcons name="party-popper" size={24} color="white" />
        </View>

        <View style={styles.agenciaTexts}>
          <Text style={styles.agenciaName}>Festa dia da limpeza</Text>
          <Text style={styles.agencialocal}>praça das oliveiras, N° 69</Text>
        </View>

      </TouchableOpacity>

       <TouchableOpacity style={styles.agenciaItem} >

        <View style={styles.agenciaIcon}>
        <MaterialCommunityIcons name="party-popper" size={24} color="white" />
        </View>

        <View style={styles.agenciaTexts}>
          <Text style={styles.agenciaName}>Fora poluição</Text>
          <Text style={styles.agencialocal}>rua da esperança, N° 89</Text>
        </View>

      </TouchableOpacity>

      </View>
          */}

      
      </ScrollView>

      <BottomTabBar active="telaMapa" navigation={navigation} />  
    
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  filtroContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    padding: 10,
  
  },
  Botaofiltro: {
    backgroundColor: '#cacaca',
    padding: 10,
    borderRadius: 30,
    marginHorizontal: 2,
  },
  TextoFiltro: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#000000',
  },
    mapaPreview: {
    height: 320,
    marginHorizontal: 12,
    marginBottom: 20,
    borderRadius: 20,
    overflow: 'hidden',
    backgroundColor: '#e6f2e3',
    borderWidth: 1,
    borderColor: '#d9e8d4',
  },
  mapaLoading: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  mapaExpandir: {
    position: 'absolute',
    right: 12,
    bottom: 12,
    backgroundColor: 'rgba(255,255,255,0.9)',
    borderRadius: 10,
    padding: 6,
  },
  agenciaItem: {
    width: '95%',
    height: 70,
    backgroundColor: '#ffffff',
    borderRadius: 10,
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 20,
    borderColor: '#727272',
    borderWidth: 1,
    marginHorizontal: 10,
  },
  agenciaIcon: {
    backgroundColor: '#00A86B',
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
    marginLeft: 10,
  },
  agenciaTexts: {
    marginLeft: 20,
  },
  agenciaName: {
    fontSize: 22,
    
  },
  agencialocal: {
    fontSize: 14,
    color: '#666666',
    marginLeft: 10,
  },
  
});