// API Configuration
export const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

// For Android emulator, use 10.0.2.2 instead of 127.0.0.1
// For iOS simulator, use localhost or 127.0.0.1
// For physical device, use your computer's IP address
export const getApiUrl = () => {
  // You can modify this based on your testing environment
  return API_BASE_URL;
};

