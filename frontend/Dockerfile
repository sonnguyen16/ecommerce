# Dockerfile
FROM node:20-alpine

# Tạo thư mục đích
WORKDIR /var/www

RUN apk update && apk add \
    curl

RUN apk cache clean

# Sao chép ứng dụng, lưu ý đến .dockerignore
COPY . /var/www

RUN npm install

RUN npm run build

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]

