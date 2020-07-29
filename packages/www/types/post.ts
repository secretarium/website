import Author from './author'

type PostType = {
    slug: string
    title: string
    date: string
    coverImage: {
        url: string
    }
    author: Author
    excerpt: string
    content: string
}

export default PostType
