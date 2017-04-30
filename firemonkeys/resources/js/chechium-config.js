var ChechiumConfig = {
  wms: [
     {
      name: "Vegetation Index [NDVI] 2000+ (MODIS)",
      icon: "./resources/img/wind.png",
      description: "Vegetation Index [NDVI] 2000+ (MODIS) from NASA Earth Observations (NEO).",
      url: "https://neo.sci.gsfc.nasa.gov/wms/wms?",
      layer: "MOD13A2_M_NDVI"
     },
    {
      name: "Outgoing Longwave Radiation",
      icon: "./resources/img/wind.png",
      description: "Outgoing Longwave Radiation (1 month)",
      url: "https://neo.sci.gsfc.nasa.gov/wms/wms?",
      layer: "CERES_LWFLUX_M"
    },
  {
    name: "Wind barbs",
    icon: "./resources/img/wind.png",
    description: "National Weather Service Wind Speed.",
    url: "https://digital.weather.gov/wms.php?",
    parameters:{
      srs: 'EPSG:3857'
    },
    layer: "ndfd.oceanic.windspd.windbarbs"
    },
  {
    name: "MODIS Hotspots for the past 24 hours.",
    icon: "./resources/img/firms.png",
    description: "NASA FIRMS WMS Service provides WMS feeds for the latest C6 MODIS Fire/Hotspot data.",
    url: "https://firms.modaps.eosdis.nasa.gov/wms/c6/?",
    layer: "fires24"
  },
  {
    name: "MODIS Hotspots for the past 48 hours.",
    icon: "./resources/img/firms.png",
    description: "NASA FIRMS WMS Service provides WMS feeds for the latest C6 MODIS Fire/Hotspot data.",
    url: "https://firms.modaps.eosdis.nasa.gov/wms/c6/?",
    layer: "fires48"
  },
  {
    name: "VIIRS 375m Fires/Hotspots for the past 24 hours.",
    icon: "./resources/img/firms.png",
    description: "NASA FIRMS WMS Service provides WMS feeds for the latest VIIRS Fire/Hotspot data.",
    url: "https://firms.modaps.eosdis.nasa.gov/wms/viirs/?",
    layer: "fires24"
  },
  {
    name: "VIIRS 375m Fires/Hotspots for the past 24 hours.",
    icon: "./resources/img/firms.png",
    description: "NASA FIRMS WMS Service provides WMS feeds for the latest VIIRS Fire/Hotspot data.",
    url: "https://firms.modaps.eosdis.nasa.gov/wms/viirs/?",
    layer: "fires48"
  },
  // {
  // name: "",
  // icon: "",
  // description: "",
  // url: "https://focosdecalor.conae.gov.ar/geoserver/wms",
  // layer: "FocosDeCalor"
  // },
  {
    name: "Ultimos Focos de Calor (MODIS).",
    icon: "./resources/img/conae.png",
    description: "Ultimos Focos de Calor (MODIS). CONAE.",
    url: "http://geoservicios.conae.gov.ar/geoserver/GeoServiciosCONAE/wms?",
    layer: "GeoServiciosCONAE:FocosDeCalorNPP"
  },
  {
    name: "Ultimos Focos de Calor (NPP).",
    icon: "./resources/img/conae.png",
    description: "Ultimos Focos de Calor (NPP). CONAE.",
    url: "http://geoservicios.conae.gov.ar/geoserver/GeoServiciosCONAE/wms?",
    layer: "GeoServiciosCONAE:FocosDeCalor"
  }
  ]
};