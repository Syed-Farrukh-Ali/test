Assessment:-
1) Environment Variables
 env() returns the variables written in .env files, it is recommended to never use this function outside of config files. Why? 
 On production we cache the env variables, after caching using env() function can result in a null value which can break the code. 
 So the good practice is to define all the variables of .env file into a config file and then access values from them using config() method.
2) Implicit If-else statements that could cause errors and afect the code's readability.
3) To reduce no of lines of code use ternary operator instead of if else.
4) Eloquent usage
 Eloquent usage can be made better. I didn't have access to the models but scope methods are great ways to reduce the queries inside 
 controller/repository and make code clean. Same as for accessors and mutators. For example in code, it is checking the consumer_type 
 and assigning a variable its value, these 4 lines can be eliminated by using accessor/mutator. 
 There is a difference between get() and first() methods. 
 get() brings all the matched results means no limit,
 first() gets the single result. $job->user()->get()->first(); 
 this line will get all the users of the job and then select the first one. $job->user()->first(); 
 this line will get the single user which means it is faster.
5) No usage of Form Requests Validation.
6) Response pattern
 For controllers handling APIs should follow the proper procedure of REST standards. 
 response() can send a JSON response but it will go and run through an extra check. 
 response()->json() can directly send the data as JSON and you can send relevant HTTP status codes.
7) Extra variable declaration no need of that variables.
8) Older syntax of array is used which is now depricated after PHP version 5.4.

=> getUsersJobs function in BookingRepository
I have just change the array into collection from the start to make sorting easier and remove complexity.
$emergencyJobs[] = $jobitem; to 
$emergencyJobs->push($jobitem);
I change this code
$noramlJobs = collect($noramlJobs)->each(function ($item, $key) use ($user_id) {
    $item['usercheck'] = Job::checkParticularJob($user_id, $item);
})->sortBy('due')->all();
This makes the code easier to read and understand by breaking it up into two separate functions.
$normalJobs->each(function ($item, $key) use ($user_id) {
    $item['usercheck'] = Job::checkParticularJob($user_id, $item);
});

$normalJobs = $normalJobs->sortBy('due');

=> index function in BookingController
i have changed
elseif($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID'))
{
    $response = $this->repository->getAll($request);
}
sometimes we can't the env variable directly it will cause error so we have to declare the variables in the app.php in config folder to avoid code break
 in app.php in config
 'ADMIN_ROLE_ID' => env('ADMIN_ROLE_ID'),
 'SUPERADMIN_ROLE_ID' => env('SUPERADMIN_ROLE_ID'),
elseif($request->__authenticatedUser->user_type == config('app.ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == config('app.SUPERADMIN_ROLE_ID'))
{
    $response = $this->repository->getAll($request);
}

=> getAll function in BookingRepository
We can move this statement out of if else block to remove dupliction.
$allJobs = Job::query();
I have remove single quotes from the intergers
I have used the User Model instead of DB facade


=> store function in BookingRepository
I have corrected the if statement AND was used, I replace with OR
if (!isset($data['due_date']) || $data['due_date'] == '') {
    $response['status'] = 'fail';
    $response['message'] = 'Du måste fylla in alla fält';
    $response['field_name'] = 'due_date';
    return $response;
}

if (!isset($data['due_time']) || $data['due_time'] == '') {
    $response['status'] = 'fail';
    $response['message'] = 'Du måste fylla in alla fält';
    $response['field_name'] = 'due_time';
    return $response;
}

I have change if statement 
if (isset($data['customer_phone_type'])) {
    $data['customer_phone_type'] = 'yes';
} else {
    $data['customer_phone_type'] = 'no';
}
to ternary operator to reduce the no of lines of code
$data['customer_phone_type'] = $data['customer_phone_type'] ? 'yes' : 'no';

also here
if (isset($data['customer_physical_type'])) {
    $data['customer_physical_type'] = 'yes';
    $response['customer_physical_type'] = 'yes';
} else {
    $data['customer_physical_type'] = 'no';
    $response['customer_physical_type'] = 'no';
}
replace with
$data['customer_physical_type'] = $data['customer_physical_type'] ? 'yes' : 'no';
$response['customer_physical_type'] = $data['customer_physical_type'];

if have return the $response separatly from if and else block
=> updateJob function in BookingRepository
I have change this code 
$current_translator = $job->translatorJobRel->where('completed_at', '<>', null)->first();
according to php syntax
$current_translator = $job->translatorJobRel->where('completed_at', '!=', Null)->first();

=> storeJobEmail function in BookingRepository
I have refactor this code to reduce the code
if (isset($data['address'])) {
    $job->address = ($data['address'] != '') ? $data['address'] : $user->userMeta->address;
    $job->instructions = ($data['instructions'] != '') ? $data['instructions'] : $user->userMeta->instructions;
    $job->town = ($data['town'] != '') ? $data['town'] : $user->userMeta->city;
}
$job->save();

if (!empty($job->user_email)) {
    $email = $job->user_email;
    $name = $user->name;
} else {
    $email = $user->email;
    $name = $user->name;
} 
replace with
if (isset($data['address'])) {
    $job->address = $data['address'] ? $data['address'] : $job->user->userMeta->address;
    $job->instructions = $data['instructions'] ? $data['instructions'] : $job->user->userMeta->instructions;
    $job->town = $data['town'] ? $data['town'] : $job->user->userMeta->city;
}
$job->save();

$email = $job->user_email ? $job->user_email : $job->user->email;
$name = $job->user->name;

=> getUsersJobsHistory function in BookingRepository
$page = $request->get('page');
if (isset($page)) {
    $pagenum = $page;
} else {
    $pagenum = "1";
}

$emergencyJobs = array();
$noramlJobs = array();

change in the ternary operator and initiating of empty arrays as array() is depricated after PHP 5.4, the shorthand declaration of arrays as [] has been available.
$page = $request->get('page');
$pagenum = $page ?? 1;

$emergencyJobs = [];
$normalJobs = [];

=> acceptJob function in BookingRepository
removing extra or unused variables
$adminemail = config('app.admin_email');
$adminSenderEmail = config('app.admin_sender_email');

=> removing extra variable fetchLanguageFromJobId function in TeHelper

=> isNeedToSendPush function in BookingRepository
I have used ternary operator to reduce the lines of code.

=> getUsermeta function in TeHelper
I have refactored this code using ternary operator to reduce the lines of code.
public static function getUsermeta(int $user_id, string $key = false)
{
    $user = UserMeta::where('user_id', $user_id)->first();

    if (!$key) {
        return $user->usermeta()->get()->all();
    }

    $meta = $user->usermeta()->where('key', $key)->get()->first();

    return $meta ? $meta->value : '';
}

I have refactored this sendPushNotificationToSpecificUsers function for better understanding and making it more dynamic
public function sendPushNotificationToSpecificUsers($users, $job_id, $data, $msg_text, $is_need_delay)
{
    $logger = $this->initializeLogger();

    $onesignalAppID = config('app.' . (env('APP_ENV') == 'prod' ? 'prod' : 'dev') . 'OnesignalAppID');
    $onesignalRestAuthKey = sprintf("Authorization: Basic %s", config('app.' . (env('APP_ENV') == 'prod' ? 'prod' : 'dev') . 'OnesignalApiKey'));

    $user_tags = $this->getUserTagsStringFromArray($users);

    $data = $this->prepareNotificationData($data);
    $fields = $this->prepareFields($onesignalAppID, $user_tags, $data, $msg_text, $is_need_delay);

    $response = $this->sendNotification($fields, $onesignalRestAuthKey);

    $logger->addInfo('Push send for job ' . $job_id . ' curl answer', [$response]);
}

private function initializeLogger()
{
    $logger = new Logger('push_logger');
    $logger->pushHandler(new StreamHandler(storage_path('logs/push/laravel-' . date('Y-m-d') . '.log'), Logger::DEBUG));
    $logger->pushHandler(new FirePHPHandler());
    return $logger;
}

private function prepareNotificationData($data)
{
    if ($data['notification_type'] === 'suitable_job') {
        $data['ios_sound'] = $data['immediate'] === 'no' ? 'normal_booking.mp3' : 'emergency_booking.mp3';
        $data['android_sound'] = $data['immediate'] === 'no' ? 'normal_booking' : 'emergency_booking';
    }
    return $data;
}

private function prepareFields($onesignalAppID, $user_tags, $data, $msg_text, $is_need_delay)
{
    $fields = array(
        'app_id'         => $onesignalAppID,
        'tags'           => json_decode($user_tags),
        'data'           => $data,
        'title'          => array('en' => 'DigitalTolk'),
        'contents'       => $msg_text,
        'ios_badgeType'  => 'Increase',
        'ios_badgeCount' => 1,
        'android_sound'  => $data['android_sound'],
        'ios_sound'      => $data['ios_sound']
    );

    if ($is_need_delay) {
        $fields['send_after'] = DateTimeHelper::getNextBusinessTimeString();
    }

    return json_encode($fields);
}

private function sendNotification($fields, $onesignalRestAuthKey)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://onesignal.com/api/v1/notifications",
        CURLOPT_HTTPHEADER => array('Content-Type: application/json', $onesignalRestAuthKey),
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_SSL_VERIFYPEER => FALSE
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

=> customerNotCall function in BookingRepository
extra lines of code
$duedate = $job_detail->due;
$start = date_create($duedate);
$end = date_create($completeddate);
$diff = date_diff($end, $start);
$interval = $diff->h . ':' . $diff->i . ':' . $diff->s;
extra variable declaration
$job = $job_detail;
I have replace array 
$response['status'] = 'success';
return $response;
to directly return to reduce lines
return ['status' => 'success']; 

=> distanceFeed in BookingController
replace the code 
if (isset($data['distance']) && $data['distance'] != "") {
    $distance = $data['distance'];
} else {
    $distance = "";
}
if (isset($data['time']) && $data['time'] != "") {
    $time = $data['time'];
} else {
    $time = "";
}
if (isset($data['jobid']) && $data['jobid'] != "") {
    $jobid = $data['jobid'];
}

if (isset($data['session_time']) && $data['session_time'] != "") {
    $session = $data['session_time'];
} else {
    $session = "";
}
if ($data['manually_handled'] == 'true') {
    $manually_handled = 'yes';
} else {
    $manually_handled = 'no';
}

if ($data['by_admin'] == 'true') {
    $by_admin = 'yes';
} else {
    $by_admin = 'no';
}

if (isset($data['admincomment']) && $data['admincomment'] != "") {
    $admincomment = $data['admincomment'];
} else {
    $admincomment = "";
}
with ternary operator
$distance = $data['distance'] ?? "";
$time = $data['time'] ?? "";
$jobid = $data['jobid'] ?? "";
$session = $data['session_time'] ?? "";

$manually_handled = $data['manually_handled'] === 'true' ? 'yes' : 'no';
$by_admin = $data['by_admin'] === 'true' ? 'yes' : 'no';
$admincomment = $data['admincomment'] ?? "";

=> reopen in BookingRepository
I have replaced older syntax of array with current
$data = [
    'created_at' => now(),
    'will_expire_at' => TeHelper::willExpireAt($job['due'], now()),
    'updated_at' => now(),
    'user_id' => $userid,
    'job_id' => $jobid,
    'cancel_at' => now(),
];

$datareopen = [
    'status' => 'pending',
    'created_at' => now(),
    'will_expire_at' => TeHelper::willExpireAt($job['due'], now()),
]; 

=> UserRepository
I have change the 
protected $model;
to
protected $user;
because it is related to User and for better understanding of code.