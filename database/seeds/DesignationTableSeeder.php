<?php

use Illuminate\Database\Seeder;
use App\Designation;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

      $desigs = [
                  [
                    'desig_name' => 'Assistant Manager',
                    'desig_desc' => 'Second in command, but not less important, assistant managers are essential for every busy restaurant. They assist the manager with training duties, help with decision making, and fill in if the manager has the day off.'
                  ],
                  [
                    'desig_name' => 'Baker',
                    'desig_desc' => 'Responsible for foodservice establishment’s bakeshop. Ensures products produced in the pastry shop meet quality standards established by the pastry chef and executive chef. In smaller establishments, the baker also might be responsible for pasta items.'
                  ],
                  [
                    'desig_name' => 'Banquet Manager',
                    'desig_desc' => ' Plans and oversees parties, banquets, conventions and other special events hosted or catered by the restaurant. Responsible for soliciting banquet business and ensuring customer satisfaction with all booked events. Coordinates and supervises the execution of all banquet functions to ensure the restaurant adheres to client  specifications  and that the function runs smoothly and efficiently. Possesses knowledge of food production and service and is able to perform all positions in banquet operations to supervise, direct and train banquet personnel.'
                  ],
                  [
                    'desig_name' => 'Barista',
                    'desig_desc' => 'At any coffee shop or café there is a barista making your favorite drinks. Baristas are responsible for preparing any number of specialty coffee, tea, and smoothie drinks, made-to-order for customers in a hurry.'
                  ],
                  [
                    'desig_name' => 'Bartender',
                    'desig_desc' => 'Responsible for setup, maintenance and operation of the bar. Takes drink orders from patrons or servers and prepares and serves alcoholic and non-alcoholic drinks according to standard recipes. Mixes ingredients for cocktails and serves wine and bottled or draught beer. Rings drink orders into register, collects payment and makes change. May also wash and sterilize glassware, prepare garnishes for drinks and prepare and replenish appetizers.'
                  ],
                  [
                    'desig_name' => 'Beverage Manager',
                    'desig_desc' => 'Oversees management and profitability of bars, lounges and other beverage-related outlets.'
                  ],
                  [
                    'desig_name' => 'Broiler Crook',
                    'desig_desc' => 'Responsible for grilled, broiled or roasted items prepared in the kitchen of a foodservice establishment. Portions food items prior to cooking, such as steaks or fish fillets. Other duties include carving and portioning roasts, plating and garnishing cooked items, and preparing appropriate garnishes for broiled or roasted foods. Responsible for maintaining a sanitary kitchen work station.'
                  ],
                  [
                    'desig_name' => 'Bus Person',
                    'desig_desc' => 'Serves water, bread and butter to guests and refills glasses as needed. Removes dirty dishes between courses. Clears, cleans and resets tables after customers leave.'
                  ],
                  [
                    'desig_name' => 'Busser',
                    'desig_desc' => 'An essential part of keeping a casual or fine dining restaurant clean, bussers are responsible for clearing and cleaning tables to prepare for the next customer. They may also assist servers by filling water glasses for customers.'
                  ],
                  [
                    'desig_name' => 'Cashier',
                    'desig_desc' => 'Like the drive-thru operator, cashiers must accurately record a customer’s order and handle cash to process the transaction. Cashiers must be able to listen when customers have problems or concerns with their orders and respond to their questions appropriately.'
                  ],
                  [
                    'desig_name' => 'Catering Manager',
                    'desig_desc' => 'Responsible for all catered functions from origination to execution, including delegation of responsibilities. Works on a consistent basis with sales personnel to generate new business and maintains contact with clients. Responsible for handling customer complaints and rectifying problems. Responsible for planning rental of tables, video/audio equipment, game equipment and linen. May book or recommend entertainment bands, speakers or specialty acts. Responsible for decorations, flowers and photographs.'
                  ],
                  [
                    'desig_name' => 'Chef Garde Manager',
                    'desig_desc' => 'Chef garde managers are in charge of all cold food items prepared in a fine dining kitchen. They prepare and plate salads, meat and cheese trays, and even cold desserts. Usually an entry-level position after formal culinary education, becoming a chef garde manager is a great way to gain kitchen experience.'
                  ],
                  [
                    'desig_name' => 'Counter Server',
                    'desig_desc' => 'Responsible for providing quick and efficient service to customers. Greets customers, takes their food and beverage orders, rings orders into register, and prepares and serves hot and cold drinks. Assembles food and beverage orders, checks them for completeness and accuracy, and packages orders for on-premise or takeout. Collects payments from guests and makes change. Maintains cleanliness of counters and floors.'
                  ],
                  [
                    'desig_name' => 'Demi Chef',
                    'desig_desc' => 'The demi chef at a restaurant has the role of assisting the sous chef or the chef de partie. The demi chef must make sure that all the food going out is perfect and must also make sure that their stations are clean and that all the waste is minimized. You will need to have a passion for the food that is being created and sometimes you will also have to assist with training new chefs and food preparation workers.'
                  ],
                  [
                    'desig_name' => 'Dining Room Manager',
                    'desig_desc' => 'Supervises dining room operation and coordinates foodservice activities. Supervises and trains employees, confers with food preparation employees and other personnel to plan menus and related activities. Estimates food and beverage costs and requisitions/buys supplies. May review financial transactions and monitor budget to ensure efficient operation and that expenditures stay within budget limitations. Maintains payroll and bookkeeping records.'
                  ],
                  [
                    'desig_name' => 'Dishwasher',
                    'desig_desc' => 'Essential members of any restaurant staff, dishwashers are not only responsible for making sure dishware is spotless, but they must also keep the kitchen clean and clear of garbage and hazardous clutter.'
                  ],
                  [
                    'desig_name' => 'Drive Through Operator',
                    'desig_desc' => 'When customers want food in a hurry, drive-thru operators must use active listening skills to ensure customer satisfaction. They are responsible for providing friendly customer service while using the cash register, taking orders, and delivering the food through the window.'
                  ],
                  [
                    'desig_name' => 'Executive Chef',
                    'desig_desc' => 'The department head responsible for a foodservice establishment’s kitchen/kitchens. Ensures kitchens provide nutritious, safe, eye-appealing, properly flavored food. Maintains a safe and sanitary work environment for all employees. Other duties include menu planning, budget preparation, and maintenance of payroll, food cost and other records. Specific duties involve food preparation and establishing quality standards, and training employees in cooking methods, presentation techniques, portion control and retention of nutrients.'
                  ],
                  [
                    'desig_name' => 'Expeditor',
                    'desig_desc' => 'Functions as the communications link between and among the various food production areas in the kitchen, coordinates production and assembly so servers can deliver meal orders to dining room patrons in a timely manner.'
                  ],
                  [
                    'desig_name' => 'Fast Food Cook',
                    'desig_desc' => 'Like the name suggests, fast food cooks must be able to prepare orders in a timely fashion. They work with equipment such as deep fryers, grills, and sandwich makers. Sometimes they may have to serve customers at their tables or at the drive-thru window'
                  ],
                  [
                    'desig_name' => 'Food and Beverage Director',
                    'desig_desc' => 'Oversees management, budget and operation of the foodservice outlet, catering services and kitchen, and maintains liaison with sales department to ensure maximum profitability.'
                  ],
                  [
                    'desig_name' => 'Food and Beverage Manager',
                    'desig_desc' => 'Some restaurants employ a food and beverage manager to manage inventory, ensure that the kitchen is compliant with health codes, and create drink menus that pair well with entrees. Food and beverage managers may also be put in charge of some dining room responsibilities, such as creating schedules for servers.'
                  ],
                  [
                    'desig_name' => 'Food Service Directory',
                    'desig_desc' => 'Directs the delivery of professional food services that will be a material factor in producing cost effectiveness, positive financial results, customer satisfaction and a positive public image.'
                  ],
                  [
                    'desig_name' => 'Fry/Saute Cook',
                    'desig_desc' => 'Responsible for all fried or sautéed items prepared in the kitchen of a foodservice establishment. Portions and prepares food items prior to cooking, such as fish fillets, shrimp or veal. Other duties include preparing batter or breading, plating and garnishing cooked items, and preparing appropriate garnishes for fried or sautéed foods. Responsible for maintaining a sanitary kitchen work station.'
                  ],
                  [
                    'desig_name' => 'General Manager',
                    'desig_desc' => 'General managers play a key role in every restaurant. They are responsible for hiring, firing, and training employees, overseeing general restaurant activities, and working on marketing and community outreach strategies. They may also help to set menu prices and purchase supplies.'
                  ],
                  [
                    'desig_name' => 'General Manager(full service)',
                    'desig_desc' => 'Coordinates foodservice activities of restaurant or other similar establishment. Estimates food and beverage costs and requisitions or purchases supplies, equipment, and food and beverages. Confers with food preparation and other personnel from the dining room, bar and banquet team to plan menus and related activities. Oversees cleaning and maintenance of equipment and facilities and ensures that all health and safety regulations are adhered to. Directs hiring, assignment, training, motivation and termination of personnel. Investigates and resolves food quality and service complaints. May develop marketing strategy, and implement advertising and promotional campaigns to increase business. May review financial transactions and monitor budget to ensure efficient operation and to ensure expenditures stay within budget limitations.'
                  ],
                  [
                    'desig_name' => 'General Manager(Quick Service)',
                    'desig_desc' => 'Maintains overall management responsibilities for the foodservice unit/establishment. Directs, coordinates, and participates in preparation, cooking, wrapping or packing food serviced or prepared by establishment, collects payment from in-house or take-out customers, and assembles food orders. Coordinates workers who keep business records, collect and pay accounts, order or purchase supplies, and deliver food to retail customers. Interviews, hires and trains personnel. May contact prospective customers to promote sale of prepared foods. May establish delivery routes and schedules.'
                  ],
                  [
                    'desig_name' => 'Grillers',
                    'desig_desc' => 'The grillers are a very necessary part of a restaurant that serves burgers, steaks, and other meats steak house style. Restaurant jobs offered for grillers are found at most family restaurants and grill houses, but some five-star, up market restaurants also need grillers, so you can apply at lots of places for this restaurant job.'
                  ],
                  [
                    'desig_name' => 'Host',
                    'desig_desc' => 'A host or hostess is responsible for the customers\' initial reaction in any casual or fine dining restaurant. They must smile and greet customers, then take them to their seats and distribute menus. They are also responsible for answering phone calls and scheduling reservations.'
                  ],
                  [
                    'desig_name' => 'Human ResourceManager',
                    'desig_desc' => 'Recruits and hires qualified employees, creates in-house job-training programs, and assists employees with their career needs.'
                  ],
                  [
                    'desig_name' => 'Kitchen Manager',
                    'desig_desc' => 'Supervises and coordinates activities concerning all back-of-the-house operations and personnel, including food preparation, kitchen and storeroom areas. Hires, discharges, trains, and evaluates back-of-house personnel. Purchases or requisitions food items, supplies and equipment. Plans or participates in menu planning and food production and apportions meat, vegetables and desserts, as well as food surpluses, to control costs. Supervises food preparation personnel to ensure food adheres to standards of quality to maintain cleanliness or kitchen and equipment. May meet with clients to plan special menus.'
                  ],
                  [
                    'desig_name' => 'Line Cook',
                    'desig_desc' => 'Although the duties differ depending upon the establishment, line cooks can be found in most restaurants, excluding fast food. A line cook may be responsible for one or multiple areas of the kitchen, such as the grill or fryer, depending upon the size and scale of the restaurant.'
                  ],
                  [
                    'desig_name' => 'Maitre d hotel',
                    'desig_desc' => 'Manages the dining room; trains, schedules and supervises servers, hosts and bus people.'
                  ],
                  [
                    'desig_name' => 'Maitre D',
                    'desig_desc' => 'Similar to a host or hostess, the maître d’ is the first face customers see when they enter a fine dining establishment. Maître d’s must arrange reservations and seat guests, but are also responsible for management duties in the dining room, including creating a schedule for the wait staff. The maître d’ ensures customer satisfaction above all, making it an extremely important part of any fine dining experience.'
                  ],
                  [
                    'desig_name' => 'Master Chef',
                    'desig_desc' => 'The master chef runs the kitchen and can also be called the kitchen or chef manager, and head restaurant cook. You will be in charge of the kitchen including expenses, waste removal, the other chefs, assistant, and food preparation workers. You will also train any new kitchen staff and chef and report directly to the operations manager.'
                  ],
                  [
                    'desig_name' => 'Operations manager',
                    'desig_desc' => 'The operations manager of a restaurant has the important job of making sure that the restaurant runs perfectly when there are customers and that is receives a good turn-over of people. You will oversee the kitchen staff and liaise with the head chef. You will check on the other restaurant jobs including the waitrons, floor, bar and front of house. The operations manager is in charge of the entire restaurant and will cover everything including the finances and ordering or products for the owners. No cooking skills are required as such, but you should be able to perform basic tasks in the kitchen to help the head chef if needed, the grillers, the bar and front of house.'
                  ],
                  [
                    'desig_name' => 'Pantry Cook',
                    'desig_desc' => 'Responsible for cold food items prepared in the kitchen of a foodservice establishment. Portions and prepares cold food items such as salads, cold appetizers, desserts, sandwiches, salad dressings and cold banquet platters. Responsible for maintaining a sanitary kitchen work station.'
                  ],
                  [
                    'desig_name' => 'Pastry Chef',
                    'desig_desc' => 'Responsible for the pastry shop in a foodservice establishment. Ensures that the products produced in the pastry shop meet the quality standards in conjunction with the executive chef. In a large establishment, the pastry chef usually is responsible only for pastries and candy. In a smaller establishment, the pastry chef is responsible for bakery items. The pastry chef also can be responsible for decorative centerpieces such as ice carvings, salt-dough sculptures, marzipan figures, pastillage and blown or pulled sugar. Develops recipes and prepares desserts, including cakes, pies, cookies, sauces, glazes and custards.'
                  ],
                  [
                    'desig_name' => 'Prep Cook',
                    'desig_desc' => 'Prep cooks work in casual and fine dining restaurants to ensure that the chefs have ingredients in easy reach when they are creating the evening’s dinner. If a dish calls for shredded cheese, you can bet that the prep cook has it ready to go for the chef, guaranteeing that the order gets out to the customer as quickly as possible.'
                  ],
                  [
                    'desig_name' => 'President/CEO',
                    'desig_desc' => 'Manages the entire restaurant operation; responsible for running a profitable and successful business.'
                  ],
                  [
                    'desig_name' => 'Public Relations Manager',
                    'desig_desc' => 'Helps the restaurant create and maintain a positive image; publicizes fundraisers, parties, special discounts and other newsworthy events.'
                  ],
                  [
                    'desig_name' => 'Runner',
                    'desig_desc' => 'Runners make servers’ jobs easier by delivering the food from the kitchen to the table both quickly and safely. It is their responsibility to ensure that food arrives as soon as it is ready, and at the proper temperature.'
                  ],
                  [
                    'desig_name' => 'Server',
                    'desig_desc' => 'Describes menu and daily specials, takes orders, serves food and makes sure customers have everything they need to enjoy their meals. Responsible for coordinating entire station and communicating front- and back-of-house personnel to provide a dining experience that meets or exceed guest expectations. Processes guest orders to ensure all items are prepared properly and on a timely basis. May carve meats, bone fish and fowl, prepare flaming dishes and desserts at tableside and present, open and pour wine when serving guests. Observes diners to ensure they are satisfied with food and service, responds to additional requests, and determines when the meal has been completed. Rings up bills and accepts payment or refers guests to cashier. May assist bus person in stocking, removing and resetting dishes and silverware between courses, and cleaning and resetting vacated tables.'
                  ],
                  [
                    'desig_name' => 'Short Order Cook',
                    'desig_desc' => 'Short order cooks can be found in diners and fast casual eateries, serving up quick recipes like breakfast foods, sandwiches and burgers, and even salads. They must be able to work quickly and competently, as well as prepare several orders at once.'
                  ],
                  [
                    'desig_name' => 'Sommlier',
                    'desig_desc' => 'A sommelier is a necessity in any fine eatery that serves wine. A sommelier recommends pairings to guests and servers, creates wine menus, and purchases wine.'
                  ],
                  [
                    'desig_name' => 'Soup and Sauce Cook',
                    'desig_desc' => 'Responsible for all soups and sauces prepared in the kitchen of a foodservice establishment. Prepares stock, thickening agents, soup garnishes, soups and sauces. Responsible for maintaining a sanitary kitchen work station.'
                  ],
                  [
                    'desig_name' => 'Sous Chef',
                    'desig_desc' => 'The sous chef acts second-in-command in the kitchen, directing and managing cooks and other kitchen workers, and taking over when the executive chef is absent. In a large establishment, the sous chef may be in charge of food production for one kitchen. In a smaller operation, the sous chef ensures that all food production workers are performing their duties as prescribed by the quality standards established by the executive chef. The sous chef assumes all the duties of the executive chef in the chef\'s absence.'
                  ],
                  [
                    'desig_name' => 'Wine Steward',
                    'desig_desc' => 'Selects and orders the wine for the restaurant; teaches staff how to describe, recommend and serve wine to customers.'
                  ]
                ];

      Designation::insert($desigs);
    }
}
