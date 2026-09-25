import React, { useState, useEffect } from "react";
import { View, Text, Image, StyleSheet, TextInput, TouchableOpacity, ScrollView } from "react-native";
import { MaterialCommunityIcons, Ionicons } from "@expo/vector-icons";
import EvilIcons from "@expo/vector-icons/EvilIcons";
import { LinearGradient } from 'expo-linear-gradient';
import MaterialScreen from "./MaterialScreen";
import { materiais } from "../data/materiais";
import BottomTabBar from './components/BottomTabBar';
import AppHeader from './components/AppHeader';


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

      <AppHeader></AppHeader>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scroll} >


        {/* botão escanear com a câmera */}

        <View style={styles.scanWrapper}>
          <TouchableOpacity onPress={() => navigation.navigate('TelaCamera')}>
            <LinearGradient
              colors={["#0A8443", "#5BAA4F"]}
              start={{ x: 0, y: 0.5 }}
              end={{ x: 1, y: 0.5 }}
              style={styles.scanGradient}
            >
              <View style={styles.scanIconCircle}>
                <EvilIcons name="camera" size={32} color="#FFFFFF" />
              </View>

              <View style={{ flex: 1 }}>
                <Text style={styles.scanTitle}>Escanear com a câmera</Text>
                <Text style={styles.scanSubtitle}>Aponte para o resíduo e identifique na hora</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>
        </View>


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
          // o key recria a tela a cada material, então o vídeo volta a
          // mostrar a miniatura e só toca quando a pessoa der play
          <MaterialScreen
            key={materialSelecionado}
            material={materiais[materialSelecionado]}
          />

        )}

      </ScrollView>
      {/* container fim*/}

      {/* MENU INFERIOR */}
      <BottomTabBar active="reciclagem" navigation={navigation} />

    </View>
  );
}


const styles = StyleSheet.create({

  container: {
    flex: 1,
    backgroundColor: "#F8F9EF",
  },

  scroll: {
    paddingHorizontal: 20,
    paddingTop: 18,
    paddingBottom: 20,
  },


  /* ================= BOTÃO ESCANEAR ================= */

  scanWrapper: {
    marginBottom: 16,
  },

  scanGradient: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 18,
    paddingHorizontal: 20,
    borderRadius: 28,
  },

  scanIconCircle: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: "rgba(255,255,255,0.22)",
    alignItems: "center",
    justifyContent: "center",
    marginRight: 16,
  },

  scanTitle: {
    color: "#FFFFFF",
    fontSize: 17,
    fontWeight: "700",
  },

  scanSubtitle: {
    color: "rgba(255,255,255,0.85)",
    fontSize: 13,
    marginTop: 2,
  },


  /* ================= PESQUISA ================= */

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


  /* ================= MATERIAIS ================= */

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

  buttonTextSelecionado: {
    color: '#FFFFFF',
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
    lineHeight: 19,
    color: "#8B8B8B",
  },

});