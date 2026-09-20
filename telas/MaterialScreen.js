import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Image,
  TouchableOpacity,
  Platform,
} from "react-native";

import { MaterialCommunityIcons } from "@expo/vector-icons";
import { WebView } from "react-native-webview";

export default function MaterialScreen({ material, onVoltar }) {

  const [videoTocando, setVideoTocando] = useState(false);

  const videoUrl = `https://www.youtube.com/embed/${material.videoId}?autoplay=1`;

  return (
    <View style={styles.container}>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.content}
      >

        {/* ÍCONE + NOME */}
        <View style={styles.titleContainer}>

          <Image
            source={material.icone}
            style={{
              width: 42,
              height: 42,
            }}
            resizeMode="contain"
          />


          <View>
            <Text style={styles.title}>
              {material.nome}
            </Text>

            <View style={styles.recyclable}>
              <MaterialCommunityIcons
                name="check-decagram-outline"
                size={16}
                color="#55A653"
              />

              <Text style={styles.recyclableText}>
                {material.subtitulo}
              </Text>
            </View>
          </View>

        </View>


        {/* CATEGORIA */}
        <View
          style={[
            styles.category,
            { backgroundColor: material.cor },
          ]}
        >
          <MaterialCommunityIcons
            name="recycle"
            size={20}
            color="#FFFFFF"
          />

          <Text style={styles.categoryText}>
            {material.categoria}
          </Text>
        </View>


        {/* DESCARTE */}
        <View style={styles.section}>

          <Text style={styles.sectionTitle}>
            {material.descarte}
          </Text>

          <Text style={styles.description}>
            {material.descricao}
          </Text>

        </View>


        {/* VÍDEO / IMAGEM */}
        {videoTocando ? (

          <View style={styles.videoContainer}>
            {Platform.OS === "web" ? (
              <iframe
                src={videoUrl}
                style={{ width: "100%", height: "100%", border: 0 }}
                allow="autoplay; encrypted-media"
                allowFullScreen
              />
            ) : (
              <WebView
                source={{ uri: videoUrl }}
                style={{ flex: 1 }}
                allowsFullscreenVideo
                javaScriptEnabled
              />
            )}
          </View>

        ) : (

          <TouchableOpacity
            style={styles.videoContainer}
            onPress={() => setVideoTocando(true)}
          >

            <Image
              source={{
                uri: `https://img.youtube.com/vi/${material.videoId}/hqdefault.jpg`,
              }}
              style={styles.videoImage}
            />

            <View style={styles.playButton}>
              <MaterialCommunityIcons
                name="play"
                size={30}
                color="#FFFFFF"
              />
            </View>

          </TouchableOpacity>

        )}


        {/* ALTERNATIVAS */}
        <TouchableOpacity
          style={[
            styles.alternativas,
            { backgroundColor: material.cor, opacity: 0.80 },
          ]}
        >
          <MaterialCommunityIcons
            name="plus-circle-outline"
            size={20}
            color="#FFFFFF"
          />

          <Text style={styles.alternativasText}>
            alternativas de reciclagem
          </Text>
        </TouchableOpacity>


        {/* TEMPO */}
        <View style={styles.infoBox}>

          <MaterialCommunityIcons
            name="clock-outline"
            size={16}
            color="#222222"
          />

          <Text style={styles.infoText}>
            Tempo de decomposição:
          </Text>

          <Text style={styles.infoBold}>
            {" "}{material.tempo}
          </Text>

        </View>


        {/* VOCÊ SABIA */}
        <View style={styles.didYouKnow}>

          <MaterialCommunityIcons
            name="lightbulb-outline"
            size={18}
            color="#D98B00"
          />

          <Text style={styles.didYouKnowText}>
            Você sabia?{" "}
            {material.voceSabia}
          </Text>

        </View>

      </ScrollView>

    </View>
  );
}


const styles = StyleSheet.create({

  container: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    marginHorizontal: 12,
    marginTop: 10,
    marginBottom: 10,
    paddingTop: 5,
    overflow: "hidden",
  },

  header: {
    height: 100,
    backgroundColor: "#0B6638",
    borderBottomLeftRadius: 45,
    borderBottomRightRadius: 45,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 20,
    paddingTop: 25,
  },

  backButton: {
    width: 45,
    height: 45,
    borderRadius: 23,
    alignItems: "center",
    justifyContent: "center",
  },

  headerTitle: {
    color: "#FFFFFF",
    fontSize: 20,
    fontWeight: "600",
    marginLeft: 10,
  },

  content: {
    paddingHorizontal: 20,
    paddingBottom: 40,
  },

  titleContainer: {
    flexDirection: "row",
    alignItems: "center",
    marginTop: 25,
    marginBottom: 18,
  },

  bigIcon: {
    width: 55,
    height: 55,
    borderRadius: 12,
    alignItems: "center",
    justifyContent: "center",
    marginRight: 14,
  },

  title: {
    fontSize: 24,
    color: "#111111",
    fontWeight: "500",
  },

  recyclable: {
    flexDirection: "row",
    alignItems: "center",
    marginTop: 2,
  },

  recyclableText: {
    color: "#55A653",
    fontSize: 15,
    marginLeft: 4,
  },

  category: {
    height: 34,
    borderRadius: 20,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 15,
    alignSelf: "flex-start",
    marginBottom: 20,
  },

  categoryText: {
    color: "#FFFFFF",
    fontSize: 16,
    marginLeft: 5,
  },

  section: {
    marginBottom: 18,
  },

  sectionTitle: {
    fontSize: 16,
    fontWeight: "700",
    color: "#222222",
    marginBottom: 7,
  },

  description: {
    fontSize: 13,
    lineHeight: 19,
    color: "#8B8B8B",
  },

  videoContainer: {
    width: "100%",
    height: 160,
    borderRadius: 3,
    overflow: "hidden",
    marginBottom: 14,
  },

  videoImage: {
    width: "100%",
    height: "100%",
  },

  playButton: {
    position: "absolute",
    left: "50%",
    top: "50%",
    marginLeft: -25,
    marginTop: -25,
    width: 50,
    height: 50,
    borderRadius: 25,
    backgroundColor: "rgba(0,0,0,0.35)",
    alignItems: "center",
    justifyContent: "center",
  },

  alternativas: {
    alignSelf: "flex-start",
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FF6B6B",
    borderRadius: 20,
    paddingHorizontal: 12,
    paddingVertical: 7,
    marginBottom: 14,
  },

  alternativasText: {
    color: "#FFFFFF",
    fontSize: 12,
    marginLeft: 4,
  },

  infoBox: {
    minHeight: 42,
    backgroundColor: "#EEF0E4",
    borderRadius: 20,
    paddingHorizontal: 14,
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 10,
  },

  infoText: {
    fontSize: 11,
    color: "#555555",
    marginLeft: 4,
  },

  infoBold: {
    fontSize: 11,
    color: "#777777",
    fontWeight: "600",
  },

  didYouKnow: {
    minHeight: 50,
    backgroundColor: "#FDF4DC",
    borderRadius: 20,
    paddingHorizontal: 14,
    flexDirection: "row",
    alignItems: "center",
  },

  didYouKnowText: {
    flex: 1,
    fontSize: 11,
    color: "#777777",
    marginLeft: 5,
  },

});