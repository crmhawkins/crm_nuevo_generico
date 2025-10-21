# 🕐 Sistema de Fichaje Moderno

## 📋 Descripción

Sistema de fichaje moderno y elegante diseñado para tablets, que permite a los empleados controlar su jornada laboral mediante PIN o contraseña.

## ✨ Características

### 🔐 **Autenticación Dual**
- **Login con PIN**: Acceso rápido con PIN de 4 dígitos
- **Login con contraseña**: Acceso tradicional con email/username y contraseña
- **Timeout de sesión**: 30 minutos de inactividad
- **Seguridad**: Un solo usuario activo por tablet

### 🎨 **Diseño Moderno**
- **Interfaz elegante**: Diseño glassmorphism con gradientes
- **Responsive**: Optimizado para tablets y móviles
- **Animaciones**: Transiciones suaves y efectos visuales
- **Touch-friendly**: Botones grandes para uso táctil

### ⚡ **Funcionalidades**
- **Control de jornada**: Iniciar, pausar, reanudar y finalizar
- **Contador en tiempo real**: Seguimiento preciso del tiempo trabajado
- **Múltiples usuarios**: Gestión de varios empleados
- **Logout seguro**: Confirmación antes de cerrar sesión

## 🚀 Instalación

### 1. **Ejecutar Migraciones**
```bash
php artisan migrate
```

### 2. **Configurar PINs de Usuarios**
```bash
# Configurar PINs para todos los usuarios activos
php artisan fichaje:configurar-pins

# Regenerar PINs existentes
php artisan fichaje:configurar-pins --regenerar
```

### 3. **Acceder al Sistema**
- URL: `/fichaje/login`
- Los usuarios pueden usar PIN o contraseña

## 📱 Uso del Sistema

### **Para Empleados**

1. **Acceso al Sistema**
   - Ir a `/fichaje/login`
   - Elegir método: PIN o Contraseña
   - Ingresar credenciales

2. **Control de Jornada**
   - Seleccionar usuario en el dashboard
   - Usar botones para controlar la jornada:
     - 🟢 **Iniciar Jornada**: Comienza el conteo de tiempo
     - 🟡 **Iniciar Pausa**: Pausa temporal
     - ⚫ **Finalizar Pausa**: Reanuda el trabajo
     - 🔴 **Finalizar Jornada**: Termina la jornada

3. **Cerrar Sesión**
   - Botón "Cerrar Sesión" en el header
   - Confirmación de seguridad

### **Para Administradores**

1. **Configurar Usuarios**
   ```bash
   # Ver usuarios y sus PINs
   php artisan fichaje:configurar-pins
   ```

2. **Gestionar Accesos**
   - Los usuarios pueden cambiar entre PIN y contraseña
   - Timeout automático de 30 minutos
   - Un solo usuario activo por tablet

## 🗄️ Base de Datos

### **Nuevos Campos en Tabla `users`**
- `pin`: PIN de 4 dígitos
- `pin_activo`: Si el PIN está habilitado
- `password_activa`: Si la contraseña está habilitada
- `ultimo_acceso`: Timestamp del último acceso
- `metodo_login`: Método preferido (pin/password)

## 🛠️ Estructura del Sistema

### **Archivos Creados/Modificados**

#### **Controladores**
- `app/Http/Controllers/FichajeController.php` - Controlador principal
- `app/Http/Middleware/FichajeAuth.php` - Middleware de autenticación

#### **Vistas**
- `resources/views/fichaje/login.blade.php` - Pantalla de login
- `resources/views/dashboards/dashboard_fichaje.blade.php` - Dashboard principal

#### **Rutas**
- Rutas agregadas en `routes/web.php`:
  - `GET /fichaje/login` - Pantalla de login
  - `POST /fichaje/login` - Procesar login
  - `POST /fichaje/logout` - Cerrar sesión
  - `GET /fichaje/dashboard` - Dashboard (protegido)

#### **Comandos**
- `app/Console/Commands/ConfigurarPINs.php` - Configurar PINs

#### **Migraciones**
- `database/migrations/2025_10_21_082705_add_fichaje_fields_to_users_table.php`

## 🔧 Configuración Avanzada

### **Personalizar Timeout de Sesión**
En `app/Http/Middleware/FichajeAuth.php`:
```php
// Cambiar de 30 minutos a otro valor
if ($user->ultimo_acceso && $user->ultimo_acceso->diffInMinutes(now()) > 30) {
```

### **Personalizar Diseño**
Los estilos están en las vistas Blade con CSS personalizado. Puedes modificar:
- Colores en las variables CSS
- Tamaños de botones
- Animaciones
- Layout responsive

### **Configurar Logo**
El logo se carga desde `assets/images/logo/logo.png`. Asegúrate de que existe o actualiza la ruta en las vistas.

## 🚨 Solución de Problemas

### **Error: "Column 'pin' doesn't exist"**
```bash
# Ejecutar migraciones
php artisan migrate
```

### **Error: "Class 'FichajeController' not found"**
```bash
# Limpiar cache
php artisan config:clear
php artisan route:clear
```

### **PINs no se generan**
```bash
# Verificar que los usuarios estén activos
# Ejecutar comando de configuración
php artisan fichaje:configurar-pins
```

## 📊 Monitoreo

### **Logs del Sistema**
- Los accesos se registran en `ultimo_acceso`
- Timeout automático después de 30 minutos
- Un solo usuario activo por sesión

### **Comandos Útiles**
```bash
# Ver estado de migraciones
php artisan migrate:status

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 🎯 Próximas Mejoras

- [ ] Reportes de tiempo trabajado
- [ ] Notificaciones push
- [ ] Integración con calendario
- [ ] Exportación de datos
- [ ] Configuración de horarios
- [ ] Múltiples tablets simultáneas

## 📞 Soporte

Para problemas o mejoras, contactar con el equipo de desarrollo.

---

**¡Sistema de Fichaje listo para usar! 🎉**
