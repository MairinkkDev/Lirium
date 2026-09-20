import React from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Image,
  ScrollView,
} from 'react-native';
import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';
import { GoogleMaps } from 'expo-maps';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function MapaScreen({navigation}) {
  return (
    <View style={styles.container}>
      <AppHeader/>
          <GoogleMaps.View
            style={styles.map}
            cameraPosition={{
              coordinates: {
              latitude: -23.5505,
              longitude: -46.6333,
            },
          zoom: 12,
          }}
      />
      <BottomTabBar active="mapa" navigation={navigation} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f0b7b7', 
  },
  //a cor acima é um placeholder

  map:{
    flex: 1,
  }
});