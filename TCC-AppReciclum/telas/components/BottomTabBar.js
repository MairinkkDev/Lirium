import React from 'react';
import { View, Text, TouchableOpacity, StyleSheet } from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { GREEN_MAIN, GRAY_TEXT } from './AppHeader';

const TABS = [
  { key: 'inicio', label: 'Início', route: 'Home', iconSet: 'ion', icon: 'home-outline', iconActive: 'home' },
  { key: 'reciclagem', label: 'Reciclagem', route: 'Reciclagem', iconSet: 'mci', icon: 'recycle', iconActive: 'recycle' },
  { key: 'telaMapa', label: 'Mapa', route: 'TelaMapa', iconSet: 'ion', icon: 'location-outline', iconActive: 'location' },
  { key: 'perfil', label: 'Perfil', route: 'Perfil', iconSet: 'ion', icon: 'person-outline', iconActive: 'person' },
];

export default function BottomTabBar({ active = 'inicio', navigation }) {
  const handlePress = (tab) => {
    if (!navigation) return;
    
    if (tab.route === 'Home') {
      navigation.navigate('Home');
    }if (tab.route === 'Perfil') {
      navigation.navigate('Perfil'); 
    }if (tab.route === 'TelaMapa') {
      navigation.navigate('TelaMapa');
    }if (tab.route === 'Reciclagem') {
      navigation.navigate('Reciclagem');
    }else {
      console.log(`TODO: navegar para tela "${tab.label}"`);
    }
  };

  return (
    <View style={styles.container}>
      {TABS.map((tab) => {
        const isActive = tab.key === active;
        const IconComponent = tab.iconSet === 'mci' ? MaterialCommunityIcons : Ionicons;
        const iconName = isActive ? tab.iconActive : tab.icon;

        return (
          <TouchableOpacity
            key={tab.key}
            style={styles.tabItem}
            onPress={() => handlePress(tab)}
            activeOpacity={0.8}
          >
            <View style={[styles.iconWrapper, isActive && styles.iconWrapperActive]}>
              <IconComponent
                name={iconName}
                size={20}
                color={isActive ? '#ffffff' : GREEN_MAIN}
              />
            </View>
            <Text style={[styles.label, isActive && styles.labelActive]}>
              {tab.label}
            </Text>
          </TouchableOpacity>
        );
      })}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'flex-end',
    backgroundColor: '#ffffff',
    paddingTop: 8,
    paddingBottom: 18,
    borderTopWidth: 1,
    borderTopColor: '#eeeeee',
  },
  tabItem: {
    alignItems: 'center',
    justifyContent: 'flex-end',
    flex: 1,
  },
  iconWrapper: {
    width: 34,
    height: 34,
    borderRadius: 17,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 4,
  },
  iconWrapperActive: {
    backgroundColor: GREEN_MAIN,
  },
  label: {
    fontSize: 11,
    fontWeight: '600',
    color: GREEN_MAIN,
  },
  labelActive: {
    color: GREEN_MAIN,
  },
});
