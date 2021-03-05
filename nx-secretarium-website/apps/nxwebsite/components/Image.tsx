import { getStrapiMedia } from "@nx-secretarium-website/store/ui-media";

const Image = ({image}) => {
    const imageUrl = getStrapiMedia(image);

    return (
      <img
        src={imageUrl}
        alt={image.alternativeText || image.name}
        height="300"
        width='100%'
        className="article-image"
      />
    );
}

export default Image;