import React from 'react';
import { View, StyleSheet,Image,} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';

// Caso não use Expo, troque LinearGradient por 'react-native-linear-gradient'

export const GREEN_DARK = '#0f5c2e';
export const GREEN_MAIN = '#3fa63f';
export const GREEN_LIGHT = '#c9e8bd';
export const BG_CREAM = '#f5f4ea';
export const GRAY_TEXT = '#8a8a8a';

export default function AppHeader({ height = 100 }) {
  return (
    <LinearGradient
                 colors={['#0f5c2e', '#3fa63f']}
                 start={{ x: 0, y: 0 }}
                 end={{ x: 1, y: 1 }}
                 style={styles.header}
               >
                 {/* logo  */}
                 <View style={styles.logoPlaceholder}>
                   <Image
                     source={require('../assets/logo.png')}
                     style={styles.logoImage}
                   />
                 </View> 
               </LinearGradient>
  );
}

const styles = StyleSheet.create({
  header: {
    height: 80,
    backgroundColor: '#087C20',
    justifyContent: 'center',
    alignItems: 'center',
  },
  logoImage: {
    width: 44,
    height: 44,
    resizeMode: 'contain',
  },
});
