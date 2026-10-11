import React from 'react';
import { SafeAreaView, 
  Button,
  TouchableOpacity,
  Text,
  StyleSheet, } from 'react-native';
import { useUsuario } from '../context/UsuarioContext';
import { Ionicons } from '@expo/vector-icons';

export default function OpcoesPerfilScreen({ navigation }) {
  const { sair } = useUsuario();

  const handleSair = () => {
    sair().then(() => {
      navigation.reset({ index: 0, routes: [{ name: 'Login' }] });
    });
  };

   const handleVoltar = () => {
  if (navigation) navigation.goBack();
  console.log('Voltar');
};

  return (
    <SafeAreaView>
      <Button
        title="Editar perfil"
        onPress={() => navigation.navigate('EditarPerfil')}
      />
      <Button title="Sair" onPress={handleSair} />

       <TouchableOpacity
        style={styles.backButton}
        onPress={handleVoltar}
        activeOpacity={0.85}
      >
        <Ionicons
          name="chevron-back"
          size={20}
          color={GREEN_DARK}
        />
      </TouchableOpacity>

    </SafeAreaView>
  );
}


const GREEN_DARK = '#0f5c2e';

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  backButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#c9e8bd',
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,
    marginLeft: 10,
  },
});