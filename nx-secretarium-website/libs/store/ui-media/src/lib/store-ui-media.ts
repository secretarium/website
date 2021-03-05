import { getStrapiURL } from '../../../ui-data/src/lib/store-ui-data';

export function getStrapiMedia(media) {
  const imageUrl = media.url.startsWith("/")
    ? getStrapiURL(media.url)
    : media.url;
  return imageUrl;
}