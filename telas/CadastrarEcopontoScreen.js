import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  StatusBar,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

import AppHeader, { GREEN_DARK, GREEN_MAIN, BG_CREAM, GRAY_TEXT } from './components/AppHeader';
import BottomTabBar from './components/BottomTabBar';

export default function CadastrarEcopontoScreen({ navigation }) {
  const [nome, setNome] = useState('');
  const [descricao, setDescricao] = useState('');
  const [endereco, setEndereco] = useState('');
  const [horario, setHorario] = useState('');
  const [materiais, setMateriais] = useState('');

  const handleVoltar = () => {
    if (navigation) navigation.goBack();
  };

  const handleAdicionar = () => {
    console.log('Cadastrar ecoponto', {
      nome,
      descricao,
      endereco,
      horario,
      materiais,
    });
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" />

      <AppHeader height={90} />

      <SafeAreaView style={styles.body}>
        <KeyboardAvoidingView
          behavior={Platform.OS === 'ios' ? 'padding' : undefined}
          style={{ flex: 1 }}
        >
          {/* Cabeçalho da tela com voltar + título */}
          <View style={styles.titleRow}>
            <TouchableOpacity onPress={handleVoltar} activeOpacity={0.8}>
              <Ionicons name="chevron-back" size={22} color="#333333" />
            </TouchableOpacity>
            <Ionicons
              name="checkmark-circle-outline"
              size={18}
              color={GREEN_MAIN}
              style={{ marginLeft: 8, marginRight: 6 }}
            />
            <Text style={styles.title}>Cadastrar ecoponto</Text>
          </View>

          <ScrollView
            contentContainerStyle={styles.scrollContent}
            keyboardShouldPersistTaps="handled"
            showsVerticalScrollIndicator={false}
          >
            <FormField label="Nome" value={nome} onChangeText={setNome} />
            <FormField label="Descrição" value={descricao} onChangeText={setDescricao} />
            <FormField label="Endereço" value={endereco} onChangeText={setEndereco} />
            <FormField
              label="Horário de funcionamento"
              value={horario}
              onChangeText={setHorario}
            />

            {/* Campo Materiais (dropdown) */}
            <Text style={styles.label}>Materiais</Text>
            <TouchableOpacity style={styles.selectBox} activeOpacity={0.8}>
              <Text style={styles.selectText}>{materiais || ''}</Text>
              <Ionicons name="chevron-down" size={18} color={GRAY_TEXT} />
            </TouchableOpacity>

            {/* Botão de adicionar (material / confirmar) */}
            <TouchableOpacity
              style={styles.addButton}
              onPress={handleAdicionar}
              activeOpacity={0.85}
            >
              <Ionicons name="add" size={26} color="#ffffff" />
            </TouchableOpacity>
          </ScrollView>
        </KeyboardAvoidingView>

        <BottomTabBar active="perfil" navigation={navigation} />
      </SafeAreaView>
    </View>
  );
}

function FormField({ label, value, onChangeText }) {
  return (
    <View style={styles.fieldWrapper}>
      <Text style={styles.label}>{label}</Text>
      <TextInput
        style={styles.input}
        value={value}
        onChangeText={onChangeText}
        placeholderTextColor={GRAY_TEXT}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: GREEN_DARK,
  },
  body: {
    flex: 1,
    backgroundColor: BG_CREAM,
  },
  titleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingTop: 18,
    paddingBottom: 8,
  },
  title: {
    fontSize: 16,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingBottom: 24,
  },
  fieldWrapper: {
    marginBottom: 16,
  },
  label: {
    fontSize: 13,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 6,
  },
  input: {
    backgroundColor: '#ffffff',
    borderRadius: 12,
    paddingHorizontal: 16,
    height: 46,
    fontSize: 14,
    color: '#333333',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 3,
    elevation: 1,
  },
  selectBox: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#ffffff',
    borderRadius: 12,
    paddingHorizontal: 16,
    height: 46,
    marginBottom: 24,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 3,
    elevation: 1,
  },
  selectText: {
    fontSize: 14,
    color: '#333333',
  },
  addButton: {
    alignSelf: 'center',
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: GREEN_MAIN,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 4,
  },
});
