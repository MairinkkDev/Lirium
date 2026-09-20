import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import Svg, { Path } from 'react-native-svg';



function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
  const angleInRadians = ((angleInDegrees - 180) * Math.PI) / 180.0;
  return {
    x: centerX + radius * Math.cos(angleInRadians),
    y: centerY + radius * Math.sin(angleInRadians),
  };
}

// Gera o "d" de um <Path> descrevendo um arco de startAngle até endAngle
// (0° = ponta esquerda, 90° = topo, 180° = ponta direita)
function describeArc(cx, cy, radius, startAngle, endAngle) {
  const start = polarToCartesian(cx, cy, radius, endAngle);
  const end = polarToCartesian(cx, cy, radius, startAngle);
  const largeArcFlag = endAngle - startAngle <= 180 ? '0' : '1';
  return ['M', start.x, start.y, 'A', radius, radius, 0, largeArcFlag, 0, end.x, end.y].join(' ');
}

export default function GaugeChart({
  value,
  max,
  label,
  size = 220,
  strokeWidth = 16,
  activeColor = '#3fa63f',
  trackColor = '#e2e2e2',
  valueFormatter = (v) => v.toLocaleString('pt-BR'),
}) {
  const percent = max > 0 ? Math.min(Math.max(value / max, 0), 1) : 0;
  const angle = percent * 180;

  const radius = size / 2 - strokeWidth;
  const cx = size / 2;
  const cy = size / 2;

  const trackPath = describeArc(cx, cy, radius, 0, 180);
  const valuePath = describeArc(cx, cy, radius, 0, angle);

  return (
    <View style={[styles.container, { width: size, height: size / 2 + 30 }]}>
      <Svg width={size} height={size / 2 + strokeWidth}>
        <Path
          d={trackPath}
          stroke={trackColor}
          strokeWidth={strokeWidth}
          strokeLinecap="round"
          fill="none"
        />
        {percent > 0 && (
          <Path
            d={valuePath}
            stroke={activeColor}
            strokeWidth={strokeWidth}
            strokeLinecap="round"
            fill="none"
          />
        )}
      </Svg>

      <View style={styles.labelWrapper}>
        <Text style={styles.value}>{valueFormatter(value)}</Text>
        {label ? <Text style={styles.label}>{label}</Text> : null}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    alignItems: 'center',
    justifyContent: 'flex-start',
  },
  labelWrapper: {
    marginTop: -34,
    alignItems: 'center',
  },
  value: {
    fontSize: 22,
    fontWeight: '800',
    color: '#333333',
  },
  label: {
    fontSize: 13,
    color: '#333333',
    marginTop: 2,
  },
});
