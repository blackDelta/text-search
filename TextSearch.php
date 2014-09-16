<?php
class TextSearch
{
    /**
     * @var $data
     */
    private $data;

    /**
     * @var $input
     */
    private $input;

    /**
     * @var $message
     */
    private $message;

    /**
     * @var array $total_occurance
     */
    private $total_occurance = array();

    /**
     * @var array $word_info
     */
    private $word_info = array();
    /**
     * @param $pattern
     * @param $data
     * @return bool
     */
    private function calculate_occurance($pattern, $data)
    {
        if($pattern == '' or $data == '')
        {
            $this->set_message('error','invalid input');
            return false;
        }

        $this->data = $data;
        $this->input = $pattern;

        $pattern_length = strlen($data);
        $input_length = strlen($pattern);
        $this->total_occurance = array();

        for ($i = 0; $i < ($pattern_length-$input_length) + 1; $i++) {
            $j = 0;
            while ($this->data[$i+$j] == $this->input[$j] and  $input_length > $j) {
                $j++;
            }
            if ($j == $input_length){
                array_push($this->total_occurance,$i);
            }
        }
    }

    /**
     * @param $pattern
     * @param $data
     * @return array
     */
    public function get_total_occurance($pattern, $data)
    {
        $this->calculate_occurance($pattern,$data);
        return $this->total_occurance;
    }

    /**
     * @return bool|int
     */
    public function calculate_words()
    {
        if($this->data == '')
        {
            $this->set_message('error','Subject not set');
            return false;
        }
        $words = 0;
        $lines = explode('\n\r',$this->data);
        foreach ($lines as $line) {
            $words += count(explode(' ',$line));
        }

       $this->word_info['words_count'] =  $words;
    }

    /**
     * @return bool|int
     */
    public function get_total_words()
    {
        if(!isset($this->word_info['words_count']))
            $this->calculate_words();

        return $this->word_info['words_count'];
    }

    public function get_word_length($criteria = 'min')
    {
        if($this->data == '')
        {
            $this->set_message('error','Subject not set');
            return false;
        }

        $total_words = str_replace('\n\r',' ',$this->data);
        $count = 0;
        if($criteria == 'max'){
            $count = strlen(array_reduce(explode(' ',$total_words),
                function ($k,$v) { return (strlen($k) > strlen($v)) ? $k : $v; }
            ));
        }
        if($criteria == 'min')
        {
            $count = strlen(array_reduce(explode(' ',$total_words),
                function ($k,$v) {
                    if (!$k) return PHP_INT_MAX;
                    return (strlen($k) < strlen($v)) ? $k : $v;
                }
            ));
        }
        return $count;
    }

    public function get_avarage_word_length()
    {
        return 0;
    }

    public function get_newline_char_length()
    {
        return 0;
    }

    public function get_longest_recurrence()
    {

    }
    public function get_message()
    {
        return $this->message;
    }
    public function set_message($message)
    {
        $this->message = $message;
    }
}

$rd = new RD();
//$rd->get_total_occurance('abra','abracadabra');

$subject = 'abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n abraca dabra abr abra cadabra \n ';

print_r($rd->get_total_occurance('abra',$subject));
echo "<br>";
echo "total words Count: ".$rd->get_total_words() . "<br>";
echo "Number of letters in the shortest word: ".$rd->get_word_length('min') . "<br>";
echo "Number of letters in the longest word: ".$rd->get_word_length('max') . "<br>";
