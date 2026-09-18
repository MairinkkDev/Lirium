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

import { Ionicons, MaterialCommunityIcons, } from '@expo/vector-icons';
import FontAwesome6 from '@expo/vector-icons/FontAwesome6';
import { LinearGradient } from 'expo-linear-gradient';

export default function HomeScreen({ navigation }) {



  return (
    <SafeAreaView style={styles.container}>

      <StatusBar barStyle="light-content" />

      {/* fundo gradiente aqui*/}
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

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >

        {/* ================= CARROSSEL ================= */}

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          style={styles.carousel}
        >

          <View style={styles.newsCard}>

            <Image
              source={{
                uri: 'https://images.unsplash.com/photo-1604187351574-c75ca79f5807'
              }}
              style={styles.newsImage}
            />

            <View style={styles.newsText}>
              <Text style={styles.newsTitle}>
                Reciclagem
              </Text>

              <Text style={styles.newsSubtitle}>
                Pequenas atitudes,
                grandes mudanças.
              </Text>
            </View>

          </View>


          <View style={styles.newsCard}>

            <Image
              source={{
                uri: 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b'
              }}
              style={styles.newsImage}
            />

            <View style={styles.newsText}>
              <Text style={styles.newsTitle}>
                Cuidar
              </Text>

              <Text style={styles.newsSubtitle}>
                Pequenas atitudes,
                grandes mudanças.
              </Text>
            </View>

          </View>


          <View style={styles.newsCard}>

            <Image
              source={{
                uri: 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9'
              }}
              style={styles.newsImage}
            />

            <View style={styles.newsText}>
              <Text style={styles.newsTitle}>
                Preservar
              </Text>

              <Text style={styles.newsSubtitle}>
                Cuide do meio ambiente.
              </Text>
            </View>

          </View>

        </ScrollView>


        {/* ================= RECICLAGEM ================= */}

        <View style={styles.recycleBox}>

          <View style={styles.recycleHeader}>

            <View style={styles.recycleCircle}>
              <MaterialCommunityIcons
                name="recycle"
                size={30}
                color="#4D974D"
              />
            </View>

            <Text style={styles.sectionTitle}>
              O QUE DESEJA RECICLAR?
            </Text>

          </View>


          <View style={styles.materialGrid}>

            {/* PLÁSTICO */}
            <TouchableOpacity
              style={[
                styles.materialButton,
                styles.plasticButton
              ]}
                onPress={() => navigation.navigate('Reciclagem', { materialId: 'pet' })}
            >

              <View style={styles.iconBoxPlastic}>
                <FontAwesome6 name="bottle-water" size={30} color="white" />
              </View>

              <Text style={styles.plasticText}>
                Plastico
              </Text>

            </TouchableOpacity>


            {/* PAPEL */}
            <TouchableOpacity
              style={[
                styles.materialButton,
                styles.paperButton
              ]}
               onPress={() => navigation.navigate('Reciclagem', { materialId: 'papelao' })}
            >

              <View style={styles.iconBoxPaper}>
                <MaterialCommunityIcons
                  name="file-document-outline"
                  size={30}
                  color="white"
                />
              </View>

              <Text style={styles.paperText}>
                Papel
              </Text>

            </TouchableOpacity>


            {/* METAL */}
            <TouchableOpacity
              style={[
                styles.materialButton,
                styles.metalButton
              ]}
              onPress={() => navigation.navigate('Reciclagem', { materialId: 'lata' })}
            >

              <View style={styles.iconBoxMetal}>
                <MaterialCommunityIcons
                  name="cog-outline"
                  size={30}
                  color="white"
                />
              </View>

              <Text style={styles.metalText}>
                METAL
              </Text>

            </TouchableOpacity>


            {/* VIDRO */}
            <TouchableOpacity
              style={[
                styles.materialButton,
                styles.glassButton
              ]}
                onPress={() => navigation.navigate('Reciclagem', { materialId: 'vidro' })}

            >

              <View style={styles.iconBoxGlass}>
                <MaterialCommunityIcons
                  name="bottle-wine "
                  size={30}
                  color="white"
                />
              </View>

              <Text style={styles.glassText}>
                Vidro
              </Text>

            </TouchableOpacity>

          </View>

        </View>


        {/* ================= INFORMAÇÕES ================= */}

        <Text style={styles.infoTitle}>
          INFORMAÇÕES RELEVANTES
        </Text>


        <View style={styles.infoContainer}>

          {/* EVENTOS */}
          <TouchableOpacity style={styles.infoButton}>

            <MaterialCommunityIcons
              name="calendar-month-outline"
              size={38}
              color="#49A84B"
            />

            <Text style={styles.infoButtonText}>
              EVENTOS
            </Text>

          </TouchableOpacity>


          {/* ONGS */}
          <TouchableOpacity style={styles.infoButton}>

            <MaterialCommunityIcons
              name="hand-heart-outline"
              size={38}
              color="#49A84B"
            />

            <Text style={styles.infoButtonText}>
              ONGS
            </Text>

          </TouchableOpacity>

        </View>

      </ScrollView>


      {/* ================= MENU INFERIOR ================= */}

      <View style={styles.bottomMenu}>

        <TouchableOpacity style={styles.menuItem}>

          <Ionicons
            name="home"
            size={30}
            color="#55A951"
          />

          <Text style={styles.menuText}>
            Início
          </Text>

        </TouchableOpacity>


        <TouchableOpacity
          style={styles.menuItem}
          onPress={() => navigation.navigate('Reciclagem')}
        >

          <MaterialCommunityIcons
            name="recycle-variant"
            size={32}
            color="#55A951"
          />

          <Text style={styles.menuText}>
            Reciclar
          </Text>

        </TouchableOpacity>


        <TouchableOpacity style={styles.menuItem}>

          <MaterialCommunityIcons
            name="map-marker-radius"
            size={32}
            color="#55A951"
          />

          <Text style={styles.menuText}>
            Mapa
          </Text>

        </TouchableOpacity>


        <TouchableOpacity style={styles.menuItem}>

          <Ionicons
            name="person-circle"
            size={32}
            color="#55A951"
          />

          <Text style={styles.menuText}>
            Perfil
          </Text>

        </TouchableOpacity>

      </View>

    </SafeAreaView>
  );
}

const styles = StyleSheet.create({

  container: {
    flex: 1,
    backgroundColor: '#F8F9F1',
  },


  /* ================= CABEÇALHO ================= */

  header: {
    height: 80,
    backgroundColor: '#087C20',
    justifyContent: 'center',
    alignItems: 'center',
  },

  logoContainer: {
    width: 48,
    height: 48,
    borderRadius: 25,
    backgroundColor: '#4CAF50',
    justifyContent: 'center',
    alignItems: 'center',
  },


  /* ================= SCROLL ================= */

  scrollContent: {
    paddingBottom: 20,
  },


  /* ================= CARROSSEL ================= */

  carousel: {
    marginTop: 15,
    paddingLeft: 12,
  },

  newsCard: {
    width: 325,
    height: 150,
    marginRight: 14,
    borderRadius: 8,
    overflow: 'hidden',
    backgroundColor: '#126D28',
  },

  newsImage: {
    width: '100%',
    height: '100%',
    position: 'absolute',
  },

  newsText: {
    flex: 1,
    justifyContent: 'center',
    padding: 20,
    backgroundColor: 'rgba(0,80,20,0.45)',
  },

  newsTitle: {
    color: 'white',
    fontSize: 22,
    fontWeight: 'bold',
  },

  newsSubtitle: {
    color: 'white',
    marginTop: 8,
    fontSize: 13,
  },


  /* ================= RECICLAGEM ================= */

  recycleBox: {
    backgroundColor: '#FFFFFF',
    marginHorizontal: 15,
    marginTop: 18,
    borderRadius: 10,
    padding: 10,
    paddingBottom: 20,
  },

  recycleHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
  },

  recycleCircle: {
    width: 48,
    height: 48,
    borderRadius: 25,
    borderWidth: 6,
    borderColor: '#D4D7CF',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 18,
  },

  sectionTitle: {
    color: '#078A2B',
    fontSize: 17,
    fontWeight: 'bold',
  },


  /* ================= BOTÕES ================= */

  materialGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },

  materialButton: {
    width: '48%',
    height: 78,
    borderWidth: 1.5,
    borderRadius: 6,
    marginBottom: 16,
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 10,
  },


  /* PLÁSTICO */

  plasticButton: {
    borderColor: '#FF3939',
  },

  iconBoxPlastic: {
    width: 42,
    height: 42,
    borderRadius: 4,
    backgroundColor: '#FF3939',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 8,
  },

  plasticText: {
    color: '#FF3939',
    fontSize: 17,
    fontWeight: 'bold',
  },


  /* PAPEL */

  paperButton: {
    borderColor: '#2979F0',
  },

  iconBoxPaper: {
    width: 42,
    height: 42,
    borderRadius: 4,
    backgroundColor: '#2979F0',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 8,
  },

  paperText: {
    color: '#2979F0',
    fontSize: 17,
    fontWeight: 'bold',
  },


  /* METAL */

  metalButton: {
    borderColor: '#F5B719',
  },

  iconBoxMetal: {
    width: 42,
    height: 42,
    borderRadius: 4,
    backgroundColor: '#F5B719',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 8,
  },

  metalText: {
    color: '#F5B719',
    fontSize: 17,
    fontWeight: 'bold',
  },


  /* VIDRO */

  glassButton: {
    borderColor: '#65D647',
  },

  iconBoxGlass: {
    width: 42,
    height: 42,
    borderRadius: 4,
    backgroundColor: '#65D647',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 8,
  },

  glassText: {
    color: '#65D647',
    fontSize: 17,
    fontWeight: 'bold',
  },


  /* ================= INFORMAÇÕES ================= */

  infoTitle: {
    textAlign: 'center',
    color: '#078A2B',
    fontSize: 17,
    fontWeight: 'bold',
    marginTop: 10,
    marginBottom: 12,
  },

  infoContainer: {
    flexDirection: 'row',
    justifyContent: 'space-evenly',
    marginBottom: 20,
  },

  infoButton: {
    width: 115,
    height: 75,
    borderWidth: 1,
    borderColor: '#BEE5B8',
    borderRadius: 5,
    justifyContent: 'center',
    alignItems: 'center',
  },

  infoButtonText: {
    color: '#4BA64B',
    fontSize: 12,
    fontWeight: 'bold',
    marginTop: 3,
  },


  /* ================= MENU INFERIOR ================= */

  bottomMenu: {
    height: 70,
    backgroundColor: '#FFFFFF',
    borderTopWidth: 1,
    borderTopColor: '#DDDDDD',
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
  },

  menuItem: {
    alignItems: 'center',
    justifyContent: 'center',
  },

  menuText: {
    color: '#55A951',
    fontSize: 11,
    marginTop: 2,
  },

  logoImage: {
    width: 60,
    height: 60,
    resizeMode: "contain"
  },

});