export class Category {
    id: number;
    category_name: string;
    category_name_ar: string;
    parent_category_id: number;
    icon_image_id: string;
    order: number;
    sub_categories: Array<Category> = [];
}


