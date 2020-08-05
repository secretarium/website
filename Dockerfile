# FROM node:current-alpine AS base
# WORKDIR /base
# COPY packages.json ./
# COPY yarn.lock ./
# RUN yarn install --pure-lockfile --no-progress --prod
# COPY ./packages .

# FROM base AS build
# ENV NODE_ENV=production
# WORKDIR /build
# COPY --from=base /base ./
# RUN yarn install --pure-lockfile --no-progress
# RUN yarn build


# FROM node:current-alpine AS build
# WORKDIR /build
# COPY . .
# RUN yarn install --pure-lockfile --no-progress
# RUN yarn build

# FROM node:current-alpine AS production
# ENV NODE_ENV=production
# WORKDIR /app
# # COPY --from=base /base/node_modules ./
# COPY ./packages/docker/* .
# COPY --from=build /build/packages/strapi ./strapi
# COPY --from=build /build/packages/www/.next ./www/.next
# COPY --from=build /build/packages/www/public ./www/public
# COPY --from=build /build/packages/www/package.json ./www/
# RUN yarn install --pure-lockfile --no-progress --prod 
# RUN next telemetry disable


FROM node:lts-alpine AS build
WORKDIR /build
COPY . .
RUN yarn install --pure-lockfile --no-progress
ENV NODE_ENV=production
RUN yarn build
RUN cd packages/www && next telemetry disable

FROM node:current-alpine AS production
WORKDIR /app
COPY . .
# COPY --from=build /build/* .
COPY --from=build /build/packages/strapi/build ./packages/strapi/
COPY --from=build /build/packages/www/.next ./packages/www/
COPY --from=build /build/packages/www/public ./packages/www/
RUN yarn install --pure-lockfile --no-progress --prod

EXPOSE 3000
CMD yarn start