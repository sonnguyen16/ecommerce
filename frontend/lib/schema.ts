export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    unit: string;
    price: number;
    discount?: number; // Optional vì có default 0
    quantity?: number; // Optional vì có default 0
    sold?: number;     // Optional vì có default 0
    thumbnail: string;
    attributes?: Record<string, any>; // JSON có thể là object với các kiểu dữ liệu khác nhau
    category_id: number;
    shop_id: number;
    seo_title?: string; // Optional vì có thể null
    seo_description?: string; // Optional vì có thể null
    seo_keywords?: string; // Optional vì có thể null
    seo_url?: string; // Optional vì có thể null
    deleted_at?: Date; // Soft delete field
    created_at: Date;
    updated_at: Date;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    image: string;
    created_at: Date;
    updated_at: Date;
}

export interface TokenResponse {
    message: string;
    tokens: {
        access_token: {
            token: string;
            expires_at: string;
        },
        refresh_token: {
            token: string;
            expires_at: string;
        }
    }
}
