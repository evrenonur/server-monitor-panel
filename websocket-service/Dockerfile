FROM node:18-alpine

WORKDIR /app

# Install dependencies
COPY package*.json ./
RUN npm install --production

# Copy source code
COPY src/ ./src/

# Expose WebSocket port
EXPOSE 8090

# Start the service
CMD ["npm", "start"]
