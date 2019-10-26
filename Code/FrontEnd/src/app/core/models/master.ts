import {Category} from './category';
import {Rate} from './rate';
import {Request} from './request';
import {Package} from './package';
import {Currency} from './currency';

export class Master {
    id: number;
    headline: string;
    oauth_provider: string;
    url: string;
    sub_category_ids: Array<number>;
    ratings: Array<Rate> = [];
    about_headline: string;
    city_name_ar: string;
    country_name_ar: string;
    mobile_no: string;
    contact_requests: Array<Request>;
    about_text: string;
    os_version: string;
    user_id: number;
    education: string;
    certificate: string;
    categories = [];
    sub_categories: Array<Category> = [];
    sub_category = [];
    packages: Array<{}> = [{}];
    images:  Array<string>;
    platform_name_ar: string;
    overall_rating: number;
    platform_id: number;
    name: string;
    min_price: number;
    image_url: string;
    profile_image_url: string;
    is_favorite: string;

}


