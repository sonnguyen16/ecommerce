export interface User {
    id: number;
    name: string;
    email?: string;
    password: string;
    phone: string;
    province?: string | null;
    district?: string | null;
    ward?: string | null;
    address?: string | null;
    birthday?: string | null;
    gender: number;
    avatar?: string | null;
    created_at: string;
    updated_at: string;
}

export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    unit: string;
    price: number;
    sale_price: number;
    quantity: number;
    sold: number;
    thumbnail: string;
    attributes?: Record<string, any>;
    category_id: number;
    category: Category;
    shop_id: number;
    seo_title?: string;
    seo_description?: string;
    seo_url?: string;
    deleted_at?: Date;
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

export interface Cart {
    id: number;
    user_id: number;
    product_id: number;
    product: Product;
    quantity: number;
    created_at: string;
    updated_at: string;
}


export interface OrderDetail {
    id: number;
    order_id: number;
    product_id: number;
    product: Product;
    order: Order;
    price: number;
    quantity: number;
    total: number;
    status: number;
    locations: any[];
    created_at: string;
    updated_at: string;
}

export interface Order {
    id: number;
    code: string;
    user_id: number;
    user: User;
    total: number;
    province: string;
    district: string;
    ward: string;
    address: string;
    phone: string;
    name: string;
    created_at: string;
    updated_at: string;
    order_details: OrderDetail[];
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

export interface PaginationData<T> {
    data: T[];
    current_page: number | 1;
    last_page: number | 1;
    total: number | 1;
    per_page: number | 1;
    prev_page_url: string | null;
    next_page_url: string | null;
}
