<?php

namespace App\Console\Commands\User;

use App\Exceptions\User\UsernameAlreadyExistsException;
use App\Exceptions\User\EmailAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use App\Exceptions\User\UnableToCreateUser;
use Illuminate\Container\Container;
use Illuminate\Console\Command;

/**
 * Class CreateUser
 * Creates a new user with the specified credentials
 *
 * @package App\Console\Commands
 */
class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {username} {email} {password} {--balance=0} {--activate} {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $balance = (int)$this->hasOption('balance') ? $this->option('balance') : 0;
        $forceActivate = (bool)$this->option('activate');
        $admin = (bool)$this->option('admin');

        // Get registrar service from container
        $registrar = Container::getInstance()->make('registrar');

        try {
            // Call registrar service method
            $registrar->register($username, $email, $password, $balance, $forceActivate, $admin);
        } catch (UsernameAlreadyExistsException $e) {
            $this->error('User with this username already exists');

            return 1;
        } catch (EmailAlreadyExistsException $e) {
            $this->error('User with this email already exists');

            return 2;
        } catch (UnableToCreateUser $e) {
            $this->error('User has not been created. Error details: ' . $e->getMessage());

            return 4;
        }

        if ($forceActivate) {
            $this->info("User successfully created and activated!");

            return 0;
        }

        $this->info("User successfully created!");

        return 0;
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['username', InputArgument::REQUIRED, 'Username'],
            ['email', InputArgument::REQUIRED, 'Email address'],
            ['password', InputArgument::REQUIRED, 'User password'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['balance', InputOption::VALUE_OPTIONAL, 'User balance', 0],
            ['activate', InputOption::VALUE_NONE, 'The user will be activated instantly', false],
            ['admin', InputOption::VALUE_NONE, 'Allows the user administrative rights', false]
        ];
    }
}
