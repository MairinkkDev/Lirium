import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  Image,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';



export default function CadastroScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');
  const [cep, setCep] = useState('');

  const handleCriar = () => {
    
    console.log('Criar conta', { email, senha, cep });
  };

  const handleVoltar = () => {
   
    if (navigation) navigation.goBack();
    console.log('Voltar');
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      
      <LinearGradient
        colors={['#0f5c2e', '#3fa63f']}
        start={{ x: 0, y: 0 }}
        end={{ x: 1, y: 1 }}
        style={styles.header}
      >
       
        <View style={styles.logoPlaceholder}>
         <Image
            source={require('../assets/logo.png')}
            style={styles.logoImage}
          />
        </View>
      </LinearGradient>

     
      <SafeAreaView style={styles.cardWrapper}>
        <KeyboardAvoidingView
          behavior={Platform.OS === 'ios' ? 'padding' : undefined}
          style={{ flex: 1 }}
        >
          <ScrollView
            contentContainerStyle={styles.card}
            keyboardShouldPersistTaps="handled"
          >
            <Text style={styles.title}>CADASTRO</Text>

            
            <View style={styles.inputContainer}>
              <Ionicons
                name="person-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder="Email:"
                placeholderTextColor="#8a8a8a"
                value={email}
                onChangeText={setEmail}
                autoCapitalize="none"
                keyboardType="email-address"
              />
            </View>

            
            <View style={styles.inputContainer}>
              <Ionicons
                name="lock-closed-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder="Senha:"
                placeholderTextColor="#8a8a8a"
                value={senha}
                onChangeText={setSenha}
                secureTextEntry
              />
            </View>

           
            <View style={styles.inputContainer}>
              <Ionicons
                name="location-outline"
                size={20}
                color="#8a8a8a"
                style={styles.inputIcon}
              />
              <TextInput
                style={styles.input}
                placeholder="CEP:"
                placeholderTextColor="#8a8a8a"
                value={cep}
                onChangeText={setCep}
                keyboardType="numeric"
              />
            </View>

            
            <TouchableOpacity
              style={styles.criarButton}
              onPress={handleCriar}
              activeOpacity={0.85}
            >
              <Text style={styles.criarButtonText}>CRIAR</Text>
            </TouchableOpacity>

            
            <TouchableOpacity
              style={styles.backButton}
              onPress={handleVoltar}
              activeOpacity={0.85}
            >
              <Ionicons name="chevron-back" size={20} color={GREEN_DARK} />
            </TouchableOpacity>
          </ScrollView>
        </KeyboardAvoidingView>
      </SafeAreaView>
    </View>
  );
}

const GREEN_DARK = '#0f5c2e';
const GREEN_MAIN = '#3fa63f';
const GREEN_LIGHT = '#c9e8bd';
const BG_CREAM = '#f5f4ea';
const BORDER_BLUE = '#2b9fd6';
const TAM_IMG = 90;
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: GREEN_DARK,
  },
  header: {
    height: 160,
    alignItems: 'center',
    justifyContent: 'center',
  },
  logoImage: {
    width: TAM_IMG,
    height: TAM_IMG,
    resizeMode: 'contain',
  },
  cardWrapper: {
    flex: 1,
    marginTop: -30, 
  },
  card: {
    flexGrow: 1,
    backgroundColor: BG_CREAM,
    borderTopLeftRadius: 40,
    borderTopRightRadius: 40,
    borderBottomWidth: 0,
    paddingHorizontal: 28,
    paddingTop: 32,
    paddingBottom: 40,
    alignItems: 'center',
  },
  title: {
    fontSize: 24,
    fontWeight: '700',
    color: GREEN_DARK,
    letterSpacing: 1,
    marginBottom: 28,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    width: '100%',
    backgroundColor: '#ffffff',
    borderRadius: 30,
    paddingHorizontal: 18,
    height: 52,
    marginBottom: 18,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    fontSize: 15,
    color: '#333333',
  },
  criarButton: {
    width: '60%',
    height: 40,
    backgroundColor: GREEN_MAIN,
    borderRadius: 30,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 6,
    shadowColor: GREEN_MAIN,
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 3,
  },
  criarButtonText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '700',
    letterSpacing: 1,
  },
  backButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: GREEN_LIGHT,
    alignItems: 'center',
    justifyContent: 'center',
    alignSelf: 'flex-start',
    marginTop:10,
  },
});
