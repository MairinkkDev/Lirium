import React, { useState, useEffect } from "react";
import { View, Text, Image, StyleSheet, TextInput, TouchableOpacity, ScrollView } from "react-native";
import { MaterialCommunityIcons, Ionicons } from "@expo/vector-icons";
import { LinearGradient } from 'expo-linear-gradient';
import MaterialScreen from "./MaterialScreen";
import { materiais } from "../data/materiais";


const botoes = [

  {
    id: "pet",
    nome: "Garrafa PET",
    icone: require("../assets/pet-bt.png"),
    iconeSelecionado: require("../assets/pet-brancobt.png"),
  },

  {
    id: "papelao",
    nome: "Papelão",
    icone: require("../assets/papelao-bt.png"),
    iconeSelecionado: require("../assets/papelao-brancobt.png"),
  },

  {
    id: "vidro",
    nome: "Vidro",
    icone: require("../assets/vidro-bt.png"),
    iconeSelecionado: require("../assets/vidro-brancobt.png"),
  },

  {
    id: "lata",
    nome: "Lata de alumínio",
    icone: require("../assets/lata-bt.png"),
    iconeSelecionado: require("../assets/lata-brancobt.png"),
  },

  {
    id: "pilhas",
    nome: "Pilhas e baterias",
    icone: require("../assets/bateria-bt.png"),
    iconeSelecionado: require("../assets/bateria-brancobt.png"),
  },

  {
    id: "oleo",
    nome: "Óleo de cozinha",
    icone: require("../assets/oleo-bt.png"),
    iconeSelecionado: require("../assets/oleo-brancobt.png"),
  },

  {
    id: "organico",
    nome: "Resto de alimento",
    icone: require("../assets/resto-bt.png"),
    iconeSelecionado: require("../assets/resto-brancobt.png"),
  },

  {
    id: "espelho",
    nome: "Espelho",
    icone: require("../assets/espelho-bt.png"),
    iconeSelecionado: require("../assets/espelho-brancobt.png"),
  },
];


export default function ReciclagemScreen({ navigation, route }) {

  const [materialSelecionado, setMaterialSelecionado] =
    useState(route.params?.materialId ?? null);

  useEffect(() => {
    if (route.params?.materialId) {
      setMaterialSelecionado(route.params.materialId);
    }
  }, [route.params?.materialId]);

  const [busca, setBusca] = useState("");

  const botoesFiltrados = botoes.filter((botao) =>
    botao.nome.toLowerCase().includes(busca.toLowerCase())
  );


  return (
    <View style={styles.container}>


      {/* HEADER */}

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
        contentContainerStyle={styles.scroll} >


        {/*botão pesquisa inicio*/}

        <View style={styles.search}>

          <MaterialCommunityIcons
            name="magnify"
            size={27}
            color="#B7B7B7"
          />

          <TextInput
            placeholder="Buscar Material"
            placeholderTextColor="#B7B7B7"
            style={styles.searchInput}
            value={busca}
            onChangeText={setBusca}
          />

        </View>
        {/*botão pesquisa fim*/}


        {/*opções de materiais */}

        <View style={styles.grid}>

  {botoesFiltrados.map((botao) => {

    const selecionado = materialSelecionado === botao.id;

    return (
      <TouchableOpacity
        key={botao.id}
        style={[
          styles.materialButton,
          selecionado && styles.materialButtonSelecionado,
        ]}
        onPress={() => setMaterialSelecionado(botao.id)}
      >

        <Image
          source={selecionado ? botao.iconeSelecionado : botao.icone}
          style={{
            width: 42,
            height: 42,
          }}
          resizeMode="contain"
        />

                <Text
          style={[
            styles.buttonText,
            selecionado && styles.buttonTextSelecionado,
          ]}
        >
          {botao.nome}
        </Text>

      </TouchableOpacity>
    );
  })}

</View>


        {/* container caso nenhum materal seja selecionado*/}

        {materialSelecionado === null ? (

          // NENHUM MATERIAL SELECIONADO
          <View style={styles.fakeContent}>

            <Text style={styles.fakeTitle}>
              Encontre o material
            </Text>

            <Text style={styles.fakeDescription}>
              Selecione uma categoria acima para descobrir
              como fazer o descarte correto.
            </Text>

          </View>

        ) : (

          // MATERIAL SELECIONADO
          <MaterialScreen
            material={materiais[materialSelecionado]}
          />

        )}

      </ScrollView>
      {/* container fim*/}

      {/* MENU INFERIOR */}

      <View style={styles.bottomMenu}>

        <TouchableOpacity style={styles.menuItem}
          onPress={() => navigation.navigate('Home')} >

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


    </View>
  );
}


/* BOTÃO DO MENU INFERIOR */

function BottomButton({
  icon,
  text,
  active = false,
}) {

  return (
    <TouchableOpacity style={styles.bottomButton}>

      <MaterialCommunityIcons
        name={icon}
        size={31}
        color="#55A653"
      />

      <Text
        style={[
          styles.bottomText,
          active && styles.bottomTextActive,
        ]}
      >
        {text}
      </Text>

    </TouchableOpacity>
  );
}


const styles = StyleSheet.create({

  container: {
    flex: 1,
    backgroundColor: "#F8F9EF",
  },

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

  logoCircle: {
    marginTop: 15,

    width: 65,
    height: 65,

    alignItems: "center",
    justifyContent: "center",
  },
  logoImage: {
    width: 60,
    height: 60,
    resizeMode: "contain"
  },

  scroll: {
    paddingHorizontal: 20,
    paddingTop: 18,
    paddingBottom: 110,
  },

  search: {
    height: 50,
    backgroundColor: "#FFFFFF",
    borderRadius: 28,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 15,
    elevation: 4,

    shadowColor: "#000",
    shadowOpacity: 0.12,
    shadowRadius: 5,
    shadowOffset: {
      width: 0,
      height: 2,
    },
  },

  searchInput: {
    flex: 1,
    fontSize: 16,
    marginLeft: 8,
    color: "#333333",
  },

  grid: {
    flexDirection: "row",
    flexWrap: "wrap",
    marginTop: 15,
    gap: 8,
  },

  materialButton: {
    width: "23%",
    minHeight: 82,
    backgroundColor: "#E4F0DB",
    borderRadius: 22,
    alignItems: "center",
    justifyContent: "center",
    paddingHorizontal: 3,
    marginBottom: 8,
    elevation: 2,
  },

  materialButtonSelecionado: {
    backgroundColor: "#007F43",
  },

  buttonText: {
    fontSize: 9,
    textAlign: "center",
    color: "#333333",
    marginTop: 5,
  },

  fakeContent: {
    marginTop: 35,
    padding: 20,
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
  },

  fakeTitle: {
    fontSize: 22,
    fontWeight: "600",
    color: "#222222",
    marginBottom: 8,
  },

  fakeDescription: {
    fontSize: 14,
    lineHeight: 21,
    color: "#888888",
  },

  materialIcon: {
    width: 40,
    height: 40,
    resizeMode: "contain",
  },

  bottomMenu: {
    position: "absolute",
    left: 0,
    right: 0,
    bottom: 0,
    height: 82,
    backgroundColor: "#FFFFFF",
    flexDirection: "row",
    justifyContent: "space-around",
    alignItems: "center",
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    elevation: 10,
  },

  bottomButton: {
    alignItems: "center",
    justifyContent: "center",
    width: "25%",
  },

  bottomText: {
    fontSize: 12,
    color: "#55A653",
    marginTop: 2,
  },
  
   buttonTextSelecionado: {
    color: '#FFFFFF',
  },

  bottomTextActive: {
    fontWeight: "600",
  },
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

});