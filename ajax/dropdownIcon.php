<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

include ('../../../inc/includes.php');
Html::header_nocache();

Session::checkLoginUser();

if (!isset($_REQUEST['style'])) {
   throw new \RuntimeException('Required argument missing or incorrect!');
}

$iconsList = [
   'fas' => [
      'ad',
      'address-book',
      'address-card',
      'adjust',
      'air-freshener',
      'align-center',
      'align-justify',
      'align-left',
      'align-right',
      'allergies',
      'ambulance',
      'american-sign-language-interpreting',
      'anchor',
      'angle-double-down',
      'angle-double-left',
      'angle-double-right',
      'angle-double-up',
      'angle-down',
      'angle-left',
      'angle-right',
      'angle-up',
      'angry',
      'ankh',
      'apple-alt',
      'archive',
      'archway',
      'arrow-alt-circle-down',
      'arrow-alt-circle-left',
      'arrow-alt-circle-right',
      'arrow-alt-circle-up',
      'arrow-circle-down',
      'arrow-circle-left',
      'arrow-circle-right',
      'arrow-circle-up',
      'arrow-down',
      'arrow-left',
      'arrow-right',
      'arrow-up',
      'arrows-alt',
      'arrows-alt-h',
      'arrows-alt-v',
      'assistive-listening-systems',
      'asterisk',
      'at',
      'atlas',
      'atom',
      'audio-description',
      'award',
      'backspace',
      'backward',
      'balance-scale',
      'ban',
      'band-aid',
      'barcode',
      'bars',
      'baseball-ball',
      'basketball-ball',
      'bath',
      'battery-empty',
      'battery-full',
      'battery-half',
      'battery-quarter',
      'battery-three-quarters',
      'bed',
      'beer',
      'bell',
      'bell-slash',
      'bezier-curve',
      'bible',
      'bicycle',
      'binoculars',
      'birthday-cake',
      'blender',
      'blender-phone',
      'blind',
      'bold',
      'bolt',
      'bomb',
      'bone',
      'bong',
      'book',
      'book-dead',
      'book-open',
      'book-reader',
      'bookmark',
      'bowling-ball',
      'box',
      'box-open',
      'boxes',
      'braille',
      'brain',
      'briefcase',
      'briefcase-medical',
      'broadcast-tower',
      'broom',
      'brush',
      'bug',
      'building',
      'bullhorn',
      'bullseye',
      'burn',
      'bus',
      'bus-alt',
      'business-time',
      'calculator',
      'calendar',
      'calendar-alt',
      'calendar-check',
      'calendar-minus',
      'calendar-plus',
      'calendar-times',
      'camera',
      'camera-retro',
      'campground',
      'cannabis',
      'capsules',
      'car',
      'car-alt',
      'car-battery',
      'car-crash',
      'car-side',
      'caret-down',
      'caret-left',
      'caret-right',
      'caret-square-down',
      'caret-square-left',
      'caret-square-right',
      'caret-square-up',
      'caret-up',
      'cart-arrow-down',
      'cart-plus',
      'cat',
      'certificate',
      'chair',
      'chalkboard',
      'chalkboard-teacher',
      'charging-station',
      'chart-area',
      'chart-bar',
      'chart-line',
      'chart-pie',
      'check',
      'check-circle',
      'check-double',
      'check-square',
      'chess',
      'chess-bishop',
      'chess-board',
      'chess-king',
      'chess-knight',
      'chess-pawn',
      'chess-queen',
      'chess-rook',
      'chevron-circle-down',
      'chevron-circle-left',
      'chevron-circle-right',
      'chevron-circle-up',
      'chevron-down',
      'chevron-left',
      'chevron-right',
      'chevron-up',
      'child',
      'church',
      'circle',
      'circle-notch',
      'city',
      'clipboard',
      'clipboard-check',
      'clipboard-list',
      'clock',
      'clone',
      'closed-captioning',
      'cloud',
      'cloud-download-alt',
      'cloud-meatball',
      'cloud-moon',
      'cloud-moon-rain',
      'cloud-rain',
      'cloud-showers-heavy',
      'cloud-sun',
      'cloud-sun-rain',
      'cloud-upload-alt',
      'cocktail',
      'code',
      'code-branch',
      'coffee',
      'cog',
      'cogs',
      'coins',
      'columns',
      'comment',
      'comment-alt',
      'comment-dollar',
      'comment-dots',
      'comment-slash',
      'comments',
      'comments-dollar',
      'compact-disc',
      'compass',
      'compress',
      'concierge-bell',
      'cookie',
      'cookie-bite',
      'copy',
      'copyright',
      'couch',
      'credit-card',
      'crop',
      'crop-alt',
      'cross',
      'crosshairs',
      'crow',
      'crown',
      'cube',
      'cubes',
      'cut',
      'database',
      'deaf',
      'democrat',
      'desktop',
      'dharmachakra',
      'diagnoses',
      'dice',
      'dice-d20',
      'dice-d6',
      'dice-five',
      'dice-four',
      'dice-one',
      'dice-six',
      'dice-three',
      'dice-two',
      'digital-tachograph',
      'directions',
      'divide',
      'dizzy',
      'dna',
      'dog',
      'dollar-sign',
      'dolly',
      'dolly-flatbed',
      'donate',
      'door-closed',
      'door-open',
      'dot-circle',
      'dove',
      'download',
      'drafting-compass',
      'dragon',
      'draw-polygon',
      'drum',
      'drum-steelpan',
      'drumstick-bite',
      'dumbbell',
      'dungeon',
      'edit',
      'eject',
      'ellipsis-h',
      'ellipsis-v',
      'envelope',
      'envelope-open',
      'envelope-open-text',
      'envelope-square',
      'equals',
      'eraser',
      'euro-sign',
      'exchange-alt',
      'exclamation',
      'exclamation-circle',
      'exclamation-triangle',
      'expand',
      'expand-arrows-alt',
      'external-link-alt',
      'external-link-square-alt',
      'eye',
      'eye-dropper',
      'eye-slash',
      'fast-backward',
      'fast-forward',
      'fax',
      'feather',
      'feather-alt',
      'female',
      'fighter-jet',
      'file',
      'file-alt',
      'file-archive',
      'file-audio',
      'file-code',
      'file-contract',
      'file-csv',
      'file-download',
      'file-excel',
      'file-export',
      'file-image',
      'file-import',
      'file-invoice',
      'file-invoice-dollar',
      'file-medical',
      'file-medical-alt',
      'file-pdf',
      'file-powerpoint',
      'file-prescription',
      'file-signature',
      'file-upload',
      'file-video',
      'file-word',
      'fill',
      'fill-drip',
      'film',
      'filter',
      'fingerprint',
      'fire',
      'fire-extinguisher',
      'first-aid',
      'fish',
      'fist-raised',
      'flag',
      'flag-checkered',
      'flag-usa',
      'flask',
      'flushed',
      'folder',
      'folder-minus',
      'folder-open',
      'folder-plus',
      'font',
      'football-ball',
      'forward',
      'frog',
      'frown',
      'frown-open',
      'funnel-dollar',
      'futbol',
      'gamepad',
      'gas-pump',
      'gavel',
      'gem',
      'genderless',
      'ghost',
      'gift',
      'glass-martini',
      'glass-martini-alt',
      'glasses',
      'globe',
      'globe-africa',
      'globe-americas',
      'globe-asia',
      'golf-ball',
      'gopuram',
      'graduation-cap',
      'greater-than',
      'greater-than-equal',
      'grimace',
      'grin',
      'grin-alt',
      'grin-beam',
      'grin-beam-sweat',
      'grin-hearts',
      'grin-squint',
      'grin-squint-tears',
      'grin-stars',
      'grin-tears',
      'grin-tongue',
      'grin-tongue-squint',
      'grin-tongue-wink',
      'grin-wink',
      'grip-horizontal',
      'grip-vertical',
      'h-square',
      'hammer',
      'hamsa',
      'hand-holding',
      'hand-holding-heart',
      'hand-holding-usd',
      'hand-lizard',
      'hand-paper',
      'hand-peace',
      'hand-point-down',
      'hand-point-left',
      'hand-point-right',
      'hand-point-up',
      'hand-pointer',
      'hand-rock',
      'hand-scissors',
      'hand-spock',
      'hands',
      'hands-helping',
      'handshake',
      'hanukiah',
      'hashtag',
      'hat-wizard',
      'hdd',
      'heading',
      'headphones',
      'headphones-alt',
      'headset',
      'heart',
      'heartbeat',
      'helicopter',
      'highlighter',
      'hiking',
      'hippo',
      'history',
      'hockey-puck',
      'home',
      'horse',
      'hospital',
      'hospital-alt',
      'hospital-symbol',
      'hot-tub',
      'hotel',
      'hourglass',
      'hourglass-end',
      'hourglass-half',
      'hourglass-start',
      'house-damage',
      'hryvnia',
      'i-cursor',
      'id-badge',
      'id-card',
      'id-card-alt',
      'image',
      'images',
      'inbox',
      'indent',
      'industry',
      'infinity',
      'info',
      'info-circle',
      'italic',
      'jedi',
      'joint',
      'journal-whills',
      'kaaba',
      'key',
      'keyboard',
      'khanda',
      'kiss',
      'kiss-beam',
      'kiss-wink-heart',
      'kiwi-bird',
      'landmark',
      'language',
      'laptop',
      'laptop-code',
      'laugh',
      'laugh-beam',
      'laugh-squint',
      'laugh-wink',
      'layer-group',
      'leaf',
      'lemon',
      'less-than',
      'less-than-equal',
      'level-down-alt',
      'level-up-alt',
      'life-ring',
      'lightbulb',
      'link',
      'lira-sign',
      'list',
      'list-alt',
      'list-ol',
      'list-ul',
      'location-arrow',
      'lock',
      'lock-open',
      'long-arrow-alt-down',
      'long-arrow-alt-left',
      'long-arrow-alt-right',
      'long-arrow-alt-up',
      'low-vision',
      'luggage-cart',
      'magic',
      'magnet',
      'mail-bulk',
      'male',
      'map',
      'map-marked',
      'map-marked-alt',
      'map-marker',
      'map-marker-alt',
      'map-pin',
      'map-signs',
      'marker',
      'mars',
      'mars-double',
      'mars-stroke',
      'mars-stroke-h',
      'mars-stroke-v',
      'mask',
      'medal',
      'medkit',
      'meh',
      'meh-blank',
      'meh-rolling-eyes',
      'memory',
      'menorah',
      'mercury',
      'meteor',
      'microchip',
      'microphone',
      'microphone-alt',
      'microphone-alt-slash',
      'microphone-slash',
      'microscope',
      'minus',
      'minus-circle',
      'minus-square',
      'mobile',
      'mobile-alt',
      'money-bill',
      'money-bill-alt',
      'money-bill-wave',
      'money-bill-wave-alt',
      'money-check',
      'money-check-alt',
      'monument',
      'moon',
      'mortar-pestle',
      'mosque',
      'motorcycle',
      'mountain',
      'mouse-pointer',
      'music',
      'network-wired',
      'neuter',
      'newspaper',
      'not-equal',
      'notes-medical',
      'object-group',
      'object-ungroup',
      'oil-can',
      'om',
      'otter',
      'outdent',
      'paint-brush',
      'paint-roller',
      'palette',
      'pallet',
      'paper-plane',
      'paperclip',
      'parachute-box',
      'paragraph',
      'parking',
      'passport',
      'pastafarianism',
      'paste',
      'pause',
      'pause-circle',
      'paw',
      'peace',
      'pen',
      'pen-alt',
      'pen-fancy',
      'pen-nib',
      'pen-square',
      'pencil-alt',
      'pencil-ruler',
      'people-carry',
      'percent',
      'percentage',
      'person-booth',
      'phone',
      'phone-slash',
      'phone-square',
      'phone-volume',
      'piggy-bank',
      'pills',
      'place-of-worship',
      'plane',
      'plane-arrival',
      'plane-departure',
      'play',
      'play-circle',
      'plug',
      'plus',
      'plus-circle',
      'plus-square',
      'podcast',
      'poll',
      'poll-h',
      'poo',
      'poo-storm',
      'poop',
      'portrait',
      'pound-sign',
      'power-off',
      'pray',
      'praying-hands',
      'prescription',
      'prescription-bottle',
      'prescription-bottle-alt',
      'print',
      'procedures',
      'project-diagram',
      'puzzle-piece',
      'qrcode',
      'question',
      'question-circle',
      'quidditch',
      'quote-left',
      'quote-right',
      'quran',
      'rainbow',
      'random',
      'receipt',
      'recycle',
      'redo',
      'redo-alt',
      'registered',
      'reply',
      'reply-all',
      'republican',
      'retweet',
      'ribbon',
      'ring',
      'road',
      'robot',
      'rocket',
      'route',
      'rss',
      'rss-square',
      'ruble-sign',
      'ruler',
      'ruler-combined',
      'ruler-horizontal',
      'ruler-vertical',
      'running',
      'rupee-sign',
      'sad-cry',
      'sad-tear',
      'save',
      'school',
      'screwdriver',
      'scroll',
      'search',
      'search-dollar',
      'search-location',
      'search-minus',
      'search-plus',
      'seedling',
      'server',
      'shapes',
      'share',
      'share-alt',
      'share-alt-square',
      'share-square',
      'shekel-sign',
      'shield-alt',
      'ship',
      'shipping-fast',
      'shoe-prints',
      'shopping-bag',
      'shopping-basket',
      'shopping-cart',
      'shower',
      'shuttle-van',
      'sign',
      'sign-in-alt',
      'sign-language',
      'sign-out-alt',
      'signal',
      'signature',
      'sitemap',
      'skull',
      'skull-crossbones',
      'slash',
      'sliders-h',
      'smile',
      'smile-beam',
      'smile-wink',
      'smog',
      'smoking',
      'smoking-ban',
      'snowflake',
      'socks',
      'solar-panel',
      'sort',
      'sort-alpha-down',
      'sort-alpha-up',
      'sort-amount-down',
      'sort-amount-up',
      'sort-down',
      'sort-numeric-down',
      'sort-numeric-up',
      'sort-up',
      'spa',
      'space-shuttle',
      'spider',
      'spinner',
      'splotch',
      'spray-can',
      'square',
      'square-full',
      'square-root-alt',
      'stamp',
      'star',
      'star-and-crescent',
      'star-half',
      'star-half-alt',
      'star-of-david',
      'star-of-life',
      'step-backward',
      'step-forward',
      'stethoscope',
      'sticky-note',
      'stop',
      'stop-circle',
      'stopwatch',
      'store',
      'store-alt',
      'stream',
      'street-view',
      'strikethrough',
      'stroopwafel',
      'subscript',
      'subway',
      'suitcase',
      'suitcase-rolling',
      'sun',
      'superscript',
      'surprise',
      'swatchbook',
      'swimmer',
      'swimming-pool',
      'synagogue',
      'sync',
      'sync-alt',
      'syringe',
      'table',
      'table-tennis',
      'tablet',
      'tablet-alt',
      'tablets',
      'tachometer-alt',
      'tag',
      'tags',
      'tape',
      'tasks',
      'taxi',
      'teeth',
      'teeth-open',
      'temperature-high',
      'temperature-low',
      'terminal',
      'text-height',
      'text-width',
      'th',
      'th-large',
      'th-list',
      'theater-masks',
      'thermometer',
      'thermometer-empty',
      'thermometer-full',
      'thermometer-half',
      'thermometer-quarter',
      'thermometer-three-quarters',
      'thumbs-down',
      'thumbs-up',
      'thumbtack',
      'ticket-alt',
      'times',
      'times-circle',
      'tint',
      'tint-slash',
      'tired',
      'toggle-off',
      'toggle-on',
      'toilet-paper',
      'toolbox',
      'tooth',
      'torah',
      'torii-gate',
      'tractor',
      'trademark',
      'traffic-light',
      'train',
      'transgender',
      'transgender-alt',
      'trash',
      'trash-alt',
      'tree',
      'trophy',
      'truck',
      'truck-loading',
      'truck-monster',
      'truck-moving',
      'truck-pickup',
      'tshirt',
      'tty',
      'tv',
      'umbrella',
      'umbrella-beach',
      'underline',
      'undo',
      'undo-alt',
      'universal-access',
      'university',
      'unlink',
      'unlock',
      'unlock-alt',
      'upload',
      'user',
      'user-alt',
      'user-alt-slash',
      'user-astronaut',
      'user-check',
      'user-circle',
      'user-clock',
      'user-cog',
      'user-edit',
      'user-friends',
      'user-graduate',
      'user-injured',
      'user-lock',
      'user-md',
      'user-minus',
      'user-ninja',
      'user-plus',
      'user-secret',
      'user-shield',
      'user-slash',
      'user-tag',
      'user-tie',
      'user-times',
      'users',
      'users-cog',
      'utensil-spoon',
      'utensils',
      'vector-square',
      'venus',
      'venus-double',
      'venus-mars',
      'vial',
      'vials',
      'video',
      'video-slash',
      'vihara',
      'volleyball-ball',
      'volume-down',
      'volume-mute',
      'volume-off',
      'volume-up',
      'vote-yea',
      'vr-cardboard',
      'walking',
      'wallet',
      'warehouse',
      'water',
      'weight',
      'weight-hanging',
      'wheelchair',
      'wifi',
      'wind',
      'window-close',
      'window-maximize',
      'window-minimize',
      'window-restore',
      'wine-bottle',
      'wine-glass',
      'wine-glass-alt',
      'won-sign',
      'wrench',
      'x-ray',
      'yen-sign',
      'yin-yang'
   ],
   'fab' => [
      '500px',
      'accessible-icon',
      'accusoft',
      'acquisitions-incorporated',
      'adn',
      'adversal',
      'affiliatetheme',
      'algolia',
      'alipay',
      'amazon',
      'amazon-pay',
      'amilia',
      'android',
      'angellist',
      'angrycreative',
      'angular',
      'app-store',
      'app-store-ios',
      'apper',
      'apple',
      'apple-pay',
      'asymmetrik',
      'audible',
      'autoprefixer',
      'avianex',
      'aviato',
      'aws',
      'bandcamp',
      'behance',
      'behance-square',
      'bimobject',
      'bitbucket',
      'bitcoin',
      'bity',
      'black-tie',
      'blackberry',
      'blogger',
      'blogger-b',
      'bluetooth',
      'bluetooth-b',
      'btc',
      'buromobelexperte',
      'buysellads',
      'cc-amazon-pay',
      'cc-amex',
      'cc-apple-pay',
      'cc-diners-club',
      'cc-discover',
      'cc-jcb',
      'cc-mastercard',
      'cc-paypal',
      'cc-stripe',
      'cc-visa',
      'centercode',
      'chrome',
      'cloudscale',
      'cloudsmith',
      'cloudversify',
      'codepen',
      'codiepie',
      'connectdevelop',
      'contao',
      'cpanel',
      'creative-commons',
      'creative-commons-by',
      'creative-commons-nc',
      'creative-commons-nc-eu',
      'creative-commons-nc-jp',
      'creative-commons-nd',
      'creative-commons-pd',
      'creative-commons-pd-alt',
      'creative-commons-remix',
      'creative-commons-sa',
      'creative-commons-sampling',
      'creative-commons-sampling-plus',
      'creative-commons-share',
      'creative-commons-zero',
      'critical-role',
      'css3',
      'css3-alt',
      'cuttlefish',
      'd-and-d',
      'd-and-d-beyond',
      'dashcube',
      'delicious',
      'deploydog',
      'deskpro',
      'dev',
      'deviantart',
      'digg',
      'digital-ocean',
      'discord',
      'discourse',
      'dochub',
      'docker',
      'draft2digital',
      'dribbble',
      'dribbble-square',
      'dropbox',
      'drupal',
      'dyalog',
      'earlybirds',
      'ebay',
      'edge',
      'elementor',
      'ello',
      'ember',
      'empire',
      'envira',
      'erlang',
      'ethereum',
      'etsy',
      'evernote',
      'expeditedssl',
      'facebook',
      'facebook-f',
      'facebook-messenger',
      'facebook-square',
      'fantasy-flight-games',
      'firefox',
      'first-order',
      'first-order-alt',
      'firstdraft',
      'flickr',
      'flipboard',
      'fly',
      'font-awesome',
      'font-awesome-alt',
      'font-awesome-flag',
      'fonticons',
      'fonticons-fi',
      'fort-awesome',
      'fort-awesome-alt',
      'forumbee',
      'foursquare',
      'free-code-camp',
      'freebsd',
      'fulcrum',
      'galactic-republic',
      'galactic-senate',
      'get-pocket',
      'gg',
      'gg-circle',
      'git',
      'git-square',
      'github',
      'github-alt',
      'github-square',
      'gitkraken',
      'gitlab',
      'gitter',
      'glide',
      'glide-g',
      'gofore',
      'goodreads',
      'goodreads-g',
      'google',
      'google-drive',
      'google-play',
      'google-plus',
      'google-plus-g',
      'google-plus-square',
      'google-wallet',
      'gratipay',
      'grav',
      'gripfire',
      'grunt',
      'gulp',
      'hacker-news',
      'hacker-news-square',
      'hackerrank',
      'hips',
      'hire-a-helper',
      'hooli',
      'hornbill',
      'hotjar',
      'houzz',
      'html5',
      'hubspot',
      'ideal',
      'imdb',
      'instagram',
      'internet-explorer',
      'ioxhost',
      'itunes',
      'itunes-note',
      'java',
      'jedi-order',
      'jenkins',
      'joget',
      'joomla',
      'js',
      'js-square',
      'jsfiddle',
      'kaggle',
      'keybase',
      'keycdn',
      'kickstarter',
      'kickstarter-k',
      'korvue',
      'laravel',
      'lastfm',
      'lastfm-square',
      'leanpub',
      'less',
      'line',
      'linkedin',
      'linkedin-in',
      'linode',
      'linux',
      'lyft',
      'magento',
      'mailchimp',
      'mandalorian',
      'markdown',
      'mastodon',
      'maxcdn',
      'medapps',
      'medium',
      'medium-m',
      'medrt',
      'meetup',
      'megaport',
      'microsoft',
      'mix',
      'mixcloud',
      'mizuni',
      'modx',
      'monero',
      'napster',
      'neos',
      'nimblr',
      'node',
      'node-js',
      'npm',
      'ns8',
      'nutritionix',
      'odnoklassniki',
      'odnoklassniki-square',
      'old-republic',
      'opencart',
      'openid',
      'opera',
      'optin-monster',
      'osi',
      'page4',
      'pagelines',
      'palfed',
      'patreon',
      'paypal',
      'penny-arcade',
      'periscope',
      'phabricator',
      'phoenix-framework',
      'phoenix-squadron',
      'php',
      'pied-piper',
      'pied-piper-alt',
      'pied-piper-hat',
      'pied-piper-pp',
      'pinterest',
      'pinterest-p',
      'pinterest-square',
      'playstation',
      'product-hunt',
      'pushed',
      'python',
      'qq',
      'quinscape',
      'quora',
      'r-project',
      'ravelry',
      'react',
      'reacteurope',
      'readme',
      'rebel',
      'red-river',
      'reddit',
      'reddit-alien',
      'reddit-square',
      'redhat',
      'renren',
      'replyd',
      'researchgate',
      'resolving',
      'rev',
      'rocketchat',
      'rockrms',
      'safari',
      'sass',
      'schlix',
      'scribd',
      'searchengin',
      'sellcast',
      'sellsy',
      'servicestack',
      'shirtsinbulk',
      'shopware',
      'simplybuilt',
      'sistrix',
      'sith',
      'skyatlas',
      'skype',
      'slack',
      'slack-hash',
      'slideshare',
      'snapchat',
      'snapchat-ghost',
      'snapchat-square',
      'soundcloud',
      'speakap',
      'spotify',
      'squarespace',
      'stack-exchange',
      'stack-overflow',
      'staylinked',
      'steam',
      'steam-square',
      'steam-symbol',
      'sticker-mule',
      'strava',
      'stripe',
      'stripe-s',
      'studiovinari',
      'stumbleupon',
      'stumbleupon-circle',
      'superpowers',
      'supple',
      'teamspeak',
      'telegram',
      'telegram-plane',
      'tencent-weibo',
      'the-red-yeti',
      'themeco',
      'themeisle',
      'think-peaks',
      'trade-federation',
      'trello',
      'tripadvisor',
      'tumblr',
      'tumblr-square',
      'twitch',
      'twitter',
      'twitter-square',
      'typo3',
      'uber',
      'uikit',
      'uniregistry',
      'untappd',
      'usb',
      'ussunnah',
      'vaadin',
      'viacoin',
      'viadeo',
      'viadeo-square',
      'viber',
      'vimeo',
      'vimeo-square',
      'vimeo-v',
      'vine',
      'vk',
      'vnv',
      'vuejs',
      'weebly',
      'weibo',
      'weixin',
      'whatsapp',
      'whatsapp-square',
      'whmcs',
      'wikipedia-w',
      'windows',
      'wix',
      'wizards-of-the-coast',
      'wolf-pack-battalion',
      'wordpress',
      'wordpress-simple',
      'wpbeginner',
      'wpexplorer',
      'wpforms',
      'wpressr',
      'xbox',
      'xing',
      'xing-square',
      'y-combinator',
      'yahoo',
      'yandex',
      'yandex-international',
      'yelp',
      'yoast',
      'youtube',
      'youtube-square',
      'zhihu'
   ]
];


// https://stackoverflow.com/questions/57730640/font-awesome-in-select-menu
echo '
<script>
var x, i, j, selElmnt, a, b, c;


function generateInnerDiv(index) {
  nametag = document.createElement("span");
  nametag.textContent = selElmnt.options[index].textContent;
  icon = document.createElement("i");
  icon.setAttribute("class", selElmnt.options[index].getAttribute(\'data-icon\'));
  return [icon, nametag]
}


/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");


  innerDiv = generateInnerDiv(selElmnt.selectedIndex)
  a.appendChild(innerDiv[0]);
  a.appendChild(innerDiv[1]);


  //a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV"); //items holder
  b.setAttribute("class", "select-items select-hide");


  for (j = 1; j < selElmnt.length; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");

    innerDiv = generateInnerDiv(j);
    c.appendChild(innerDiv[0]);
    c.appendChild(innerDiv[1]);

    //c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {

      /*when an item is clicked, update the original select box,
      and the selected item:*/
      var y, i, k, s, h, l, m;
      s = this.parentNode.parentNode.getElementsByTagName("select")[0];
      h = this.parentNode.previousSibling;
      l = this.getElementsByTagName("span")[0];
      m = this.getElementsByTagName("i")[0];


      for (i = 0; i < s.length; i++) {
        if (s.options[i].innerHTML == l.innerHTML) {

          s.selectedIndex = i;
          h.getElementsByTagName("span")[0].innerHTML = l.innerHTML;
          h.getElementsByTagName("i")[0].setAttribute("class", s.options[i].getAttribute(\'data-icon\'));
          y = this.parentNode.getElementsByClassName("same-as-selected");
          for (k = 0; k < y.length; k++) {
            y[k].removeAttribute("class");
          }
          this.setAttribute("class", "same-as-selected");
          break;
        }
      }
      h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
    /*when the select box is clicked, close any other select boxes,
    and open/close the current select box:*/
    e.stopPropagation();
    closeAllSelect(this);
    this.nextSibling.classList.toggle("select-hide");
    this.classList.toggle("select-arrow-active");
  });
}

function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);


//////////////////
//How to get values from custom select
//////////////////
document.getElementById("btn1").addEventListener("click", () => {
  console.log(document.getElementById("myselect1").value)
});

document.getElementById("btn2").addEventListener("click", () => {
  console.log(document.getElementById("myselect2").value)
});
</script>
';

echo '
<script src="https://kit.fontawesome.com/9593bd7a92.js"></script>

';


echo '
<!--surround the select box with a "custom-select" DIV element. Remember to set the width:-->
<div class="custom-select" style="width:300px;">
  <select id="myselect1" name="icon">
   <option value="'.Dropdown::EMPTY_VALUE.'">'.Dropdown::EMPTY_VALUE.'</option>';
foreach ($iconsList[$_REQUEST['style']] as $icon) {
   echo '   <option data-icon="'.$_REQUEST['style'].' fa-'.$icon.' fa-4x" value="'.$icon.'">'.$icon.'</option>
';
}
echo '
  </select>
</div>
';