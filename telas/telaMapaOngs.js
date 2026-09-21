import React from 'react';

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
} from 'react-native';

import AppHeader from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';
import { Ionicons, MaterialCommunityIcons, } from '@expo/vector-icons';
import FontAwesome6 from '@expo/vector-icons/FontAwesome6';
import { LinearGradient } from 'expo-linear-gradient';
import FontAwesome from '@expo/vector-icons/FontAwesome';
import FontAwesome5 from '@expo/vector-icons/FontAwesome5';

export default function telaMapaOngs({navigation}) {
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
    
  <TouchableOpacity onPress={() => navigation.navigate('Mapa')} style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
        <Image
          source={require('./assets/Mapa.png')}
          style={styles.mapa}
        />
    </TouchableOpacity>
        
        
     {/*-----------------------agencias ---------------*/}
      
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


      
      </ScrollView>

      <BottomTabBar active="telaMapaOngs" navigation={navigation} />  
    
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
  mapa: {
    width: '100%',
    height: 400,
    resizeMode: 'contain',
    borderRadius: 10,
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
