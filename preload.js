const { contextBridge } = require('electron');

contextBridge.exposeInMainWorld('cdss', {
    platform: process.platform,
});
