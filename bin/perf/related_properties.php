<?php
require_once('../../config.php');

$db = Database::instance();

$parcel_numbers = "2114017020,2233019053,2234024008,2248007142,2329004016,2332018012,2347006016,2356017002,2501015035,2502004082,2502024006,2502024021,2502024026,2502027018,2504006140,2504006141,2504007004,2504007040,2504010136,2504015010,2504019005,2504019024,2504019050,2504020018,2504020019,2504020020,2504020022,2504020025,2504020026,2504020033,2504020037,2504020038,2504022002,2504022011,2504022013,2504022014,2504022015,2504022016,2504022020,2506035035,2507003041,2509003031,2509003096,2509003201,2509004031,2509013199,2509014093,2513007048,2513020038,2513021025,2513024008,2513026028,2513030010,2513030013,2535006023,2535009019,2535015004,2535015021,2535024006,2535024025,2535025004,2535025005,2535025023,2535025029,2535028022,2536008025,2537001002,2603021002,2604009012,2604011003,2604011019,2604012027,2604017031,2604018002,2604018003,2604018025,2604018026,2604019031,2604021006,2604021032,2604022043,2604022044,2604024003,2604029023,2611001052,2611007040,2611010012,2611010027,2611010032,2612013005,2612013006,2612013007,2612013008,2612013009,2612013010,2612013011,2612013012,2612013013,2615007013,2615007015,2616011023,2616014012,2619014006,2619026028,2620025011,2622002016,2622016014,2626012028,2626016022,2626016023,2646003013,2646016003,2647013009,4004031020,4005025012,4214008005,4260040031,4261002003,4261002004,4261002013,4261002014,4261003006,4261003014,4261003015,4261003016,4261003017,4261003020,4261003021,4261003022,4261004002,4261004004,4261004007,4261004008,4261004009,4261004010,4261004019,4261004020,4261005005,4261005015,4261005018,4261005019,4261005020,4261005021,4261005022,4261005025,4261006004,4261006005,4261006006,4261006007,4261006024,4261007011,4261008016,4261009015,4261009024,4261012003,4261012006,4261012018,4261012019,4261012020,4261012021,4261012022,4261012028,4261012029,4261012037,4261013009,4261013012,4261013013,4261013014,4261013015,4261013016,4261013017,4261013026,4261013031,4261013033,4261013034,4261013038,4261013039,4261013041,4261013043,4261013044,4261014016,4261014021,4261014022,4261015006,4261015012,4261015017,4261015019,4261015021,4261015065,4261015066,4261016002,4261016005,4261016006,4261016009,4261016011,4261016012,4261016013,4261016015,4261016016,4261016023,4261017001,4261017004,4261017007,4261017008,4261017009,4261017010,4261017012,4261017013,4261017014,4261017015,4261017023,4261017024,4261017048,4261019004,4261019005,4261019025,4261019026,4263001004,4263002020,4263002048,4263005044,4263005046,4263023007,4263025014,4263026026,4263026028,4263027001,4263029028,4263029104,4267036030,4302031028,4401007013,4401010004,4401010016,4401012048,4401026003,4401026004,4401026011,4401029012,4401030019,4401030103,4404014004,4414001017,4415001005,4415001006,5002003016,5002018010,5002018013,5002018020,5003015014,5003016016,5003018030,5005002004,5005002030,5005002031,5005004031,5005008012,5005016018,5005021016,5006007008,5006010024,5006013002,5006013011,5006017003,5006017013,5006019027,5006020017,5006023016,5006031032,5013016014,5013017017,5013017018,5013017019,5013018001,5013020013,5013020031,5014003002,5014003023,5014005006,5014006024,5014013001,5014013016,5014014013,5015026028,5016001019,5016002023,5016010001,5016010003,5016012008,5016035021,5017002003,5017002004,5017002005,5017013001,5017017011,5017018008,5022019025,5022028005,5023021014,5023021015,5023021016,5023022013,5023022015,5023022016,5023024014,5023025001,5023027016,5023027017,5023030028,5030003033,5030006021,5030017012,5034020001,5035001012,5035003001,5035004014,5035004024,5042008015,5042008016,5042009006,5044014027,5044020018,5044021017,5044021019,5044031006,5050009035,5050009036,5050010057,5050011009,5051001015,5051001022,5051001023,5051002002,5051002003,5051002004,5051002005,5051002013,5051003001,5051003002,5051003004,5051003005,5051004002,5051004004,5051004007,5051004027,5051005021,5051012016,5051019013,5051021008,5051021010,5051021029,5051022007,5051022020,5051035024,5051036041,5051036042,5052001017,5052015011,5056005017,5056017012,5056026001,5059015003,5059016010,5059019010,5059019011,5059019012,5059024013,5059025006,5059026023,5059027007,5061021040,5061024001,5075029015,5075038023,5075039027,5076014016,5077002175,5078021005,5101025008,5101028016,5104006017,5104006018,5104006026,5104007009,5104007031,5104008007,5104008025,5104008035,5104009010,5104009028,5104010006,5104010018,5104010019,5104010031,5104011019,5104015001,5104018027,5104020001,5104020035,5106003002,5106013027,5106021007,5106023014,5106025004,5106026002,5107005018,5107005024,5107006016,5107007002,5107007025,5107009013,5107010010,5107010020,5107018011,5107019013,5107019018,5107019020,5107022024,5107029013,5107029027,5107030024,5107032023,5108004020,5108023022,5109021025,5118009020,5124033003,5136010015,5142006003,5154011016,5159016005,5172005024,5172023005,5174020032,5407004011,5429001012,5436006008,5436012040,5436015019,5436017026,5436021004,5436021005,5436021007,5436022008,5436022010,5437017005,5458025012,5458025013,5458026016,5458029031,5459009016,5459009029,5459010005,5459010011,5459010026,5473037014,5473038006,5473038007,5473038008,5473039007,5473041002,5473041003,5474003008,5474004019,5474004021,5474007017,5474012034,5474017015,5474028011,5474028013,5474029026,5474029027,5476012006,5476014026,5476020022,5476020025,5477001023,5477001028,5477003046,5477005011,5478002030,5478004021,5478004022,5478007032,5478007033,5478009073,5478023012,5478023028,5478023030,5478023032,5478025013,5478025021,5478025022,5478026014,5478026015,5478026022,5478026028,5478036015,5479013020,5479013029,5479026004,5480016015,5480021013,5483022001,5484002026,5484003006,5484003012,5484003019,5484005006,5484006008,5484006010,5484006011,5484007006,5484007007,5484007008,5484007010,5484008005,5484008021,5484010024,5485006025,5485008004,5485009025,5486003026,5486003030,5486003035,5486004006,5486004007,5486004008,5486004009,5486004032,5486005015,5486006026,5486006030,5486013039,5486014027,5486018014,5492036003,5492036004,5492036020,5492037005,5492037011,5492038023,5492040032,5493003019,5493003031,5493005008,5493005022,5493009018,5493010024,5493011014,5493015008,5493015012,5493016018,5493016036,5493017006,5493019004,5493021004,5493021007,5493021015,5493022001,5493022019,5493022021,5493023005,5493023008,5493024010,5493025015,5493026008,5493026014,5493026018,5493027010,5493027020,5493032005,5493033011,5493033026,5493033028,5493033029,5493034023,5493034027,5493035003,5493035010,5493037001,5493037011,5493037013,5493037015,5501021025,5502027012,5507025008,5507026002,5507026003,5507026004,5507026005,5507026006,5507026007,5507027018,5507027019,5507027020,5512019002,5512022016,5512022019,5513003018,5513003019,5513003021,5513003022,5513003025,5513003026,5513003027,5513003028,5513003029,5513004001,5513004002,5513004003,5513004004,5513004005,5513004006,5513004007,5513004009,5513004011,5513017007,5513017008,5513017009,5513017010,5513017011,5513017012,5513017013,5513017014,5513018020,5513018021,5513018022,5513018023,5513018024,5513018025,5513018026,5513018038,5513024001,5513024002,5513024003,5513024004,5513024005,5513024006,5513024010,5522005014,5522006012,5522025006,5522027023,5522027026,5522027027,5522027029,5522028016,5522029007,5523007007,5523007023,5523009010,5523014033,5523022014,5523022015,5523026020,5525001021,5525001022,5525001036,5525001051,5525002005,5525002023,5525002024,5525003003,5525003007,5525003017,5525003020,5525003029,5525004021,5525004025,5525029016,5525029017,5525029019,5525029020,5525030003,5525030004,5525030005,5525030006,5525030007,5525031001,5525031002,5525031003,5525031006,5525031007,5525031008,5525031009,5525032017,5525032018,5525032022,5525032024,5526006005,5526006007,5526006008,5526006009,5526006026,5526006041,5526007007,5526007012,5526007013,5526007027,5526007028,5526007029,5526029001,5526029002,5526029004,5526029005,5526029010,5526029013,5526029014,5526029018,5526029022,5526029024,5526029025,5526030011,5526030013,5526030015,5526030016,5526030020,5526030021,5526030022,5526030024,5526030025,5526030026,5526030028,5526030030,5526030031,5526031003,5526031007,5526031009,5526031010,5526031011,5526031012,5526031013,5526031014,5526031017,5526031018,5526031020,5526031021,5526031027,5526032015,5526032016,5526032017,5526039004,5526039005,5526039006,5526039007,5526039009,5526039010,5526039011,5526039012,5526039013,5526039014,5526040004,5526040005,5526040007,5526040008,5526040011,5526040012,5526040015,5526040016,5526040021,5526040023,5526040025,5526040026,5526041004,5526041005,5526041006,5526041007,5526041008,5526041010,5526041011,5526041019,5526041020,5526041021,5526041022,5526041023,5526041024,5526042001,5526042002,5526042003,5526042004,5526042005,5526042007,5526042009,5526042011,5526042012,5526042017,5526042018,5526042020,5526042021,5526042022,5526042023,5526042028,5526042029,5527026008,5527026010,5527026017,5527026023,5527026024,5527026025,5527026026,5527026028,5527026033,5527027013,5527027014,5527027016,5527027019,5527027020,5527027023,5527027024,5527027025,5527027035,5527027037,5527027039,5527027040,5527027042,5527028001,5527028005,5527028007,5527028009,5527028010,5527028011,5527028013,5527028015,5527028016,5527028018,5527028019,5527028020,5527028021,5527028023,5527028024,5527028025,5527029003,5527029007,5527029008,5527029009,5527029011,5527029012,5527029014,5527029017,5527029023,5527029024,5527029025,5527029026,5527029028,5527029029,5527029030,5527030001,5527030002,5527030003,5527030004,5527030007,5527030008,5527030010,5527030027,5527037006,5527037010,5527037011,5527037014,5527037031,5527038005,5527038006,5527038011,5527038012,5527038015,5527038017,5527038018,5527038019,5527038020,5527038022,5527038023,5527038024,5527038026,5527038033,5527038034,5527039005,5527039006,5527039007,5527039009,5527039014,5527039015,5527039016,5527039023,5527039024,5527039025,5527039026,5527039030,5527040008,5527040009,5527040011,5527040012,5527040016,5527040017,5527040018,5527040019,5527040020,5527040021,5527040022,5527040023,5527040026,5527040031,5527040033,5527040039,5527040040,5527041005,5527041006,5527041015,5527041017,5527041020,5527041023,5527041025,5531019011,5531020007,5531025012,5531025024,5532001034,5532003024,5532005031,5532007010,5532007011,5532007026,5533013017,5533013027,5534021017,5534021031,5547031012,5548001017,5548001018,5548002401,5548004016,5548009010,5548009031,5548013017,5548023004,5548024048,5550015007,5550015023,5550017023,5550025015,5559020010,5565012009,5572030010,5572031016,5580004024,5580007025,5585010020,5589017035,5590006006,5680027019,5682025019,5685005006,5685005008,5685005022,5685007003,5685008027,5685011002,5685011013,5685012008,5685016008,5685027006,5685027011,5685027036,5686001017,5686001019,5686001020,5686006014,5686015019,5686022032,5686022036,5689016013,5690022005,5691012007,6023029010,6050020012,7346001008,7346001026,7346001027,7346001052,7346001058,7346001059,7346001062,7346001063,7346001064,7346001065,7346001066,7346001067,7346001070,7346002041,7346002045,7346002049,7346002050,7346003013,7346004017,7346006010,7347001032,7347001036,7347001037,7347002029,7347002032,7347003017";

$addresses_query_v1 = sprintf(
  "
  SELECT count(owner_address_and_zip) - 1 as `related_properties_count`, owner_address_and_zip
  FROM `property`
  WHERE owner_address_and_zip IN (
    SELECT
      distinct owner_address_and_zip
    FROM `property`
    WHERE parcel_number IN (
      %s
    ) AND full_mail_address <> \"\"
  )
  GROUP BY owner_address_and_zip
  HAVING `related_properties_count` > 0;
  ",
  $parcel_numbers
);

$db->query($addresses_query_v1);

$results_v1 = $db->result_array();

$related_count_map_v1 = [];
foreach ($results_v1 as $result) {
  $address =  $result['owner_address_and_zip'];
  $related_properties_count =  $result['related_properties_count'];

  // echo $address . " ";
  // echo $related_properties_count;
  // echo "\n";
  $related_count_map_v1[$address] = $related_properties_count;
}

$start = microtime(true);

$addresses_query_v2 = sprintf(
  "
  SELECT
    count(`owner_address_and_zip`) - 1 AS `related_properties_count`,
    `owner_address_and_zip`
  FROM `property`
  WHERE
    `full_mail_address` <> \"\" AND
    `owner_address_and_zip` IN (
      SELECT
        `owner_address_and_zip`
      FROM `property`
      WHERE parcel_number IN (
        %s
      )
    )
  GROUP BY owner_address_and_zip
  HAVING `related_properties_count` > 0;
  ",
  $parcel_numbers
);

$db->query($addresses_query_v2);

$results_v2 = $db->result_array();

$related_count_map_v2 = [];
foreach ($results_v2 as $result) {
  $address =  $result['owner_address_and_zip'];
  $related_properties_count =  $result['related_properties_count'];

  $related_count_map_v2[$address] = $related_properties_count;
  echo $address . " ";
  echo $related_properties_count;
  echo "\n";
}

$elapsed = microtime(true) - $start;

// Debug::dumpR($elapsed);
// Debug::dumpR($related_count_map);
