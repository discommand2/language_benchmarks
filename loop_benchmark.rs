use std::time::{Duration, Instant};

fn main() {
    let start = Instant::now();
    let runtime = Duration::new(10, 0); // 10 seconds
    let mut count_loops = 0;

    while Instant::now().duration_since(start) < runtime {
        count_loops += 1;
    }

    let per_second = count_loops / 10;
    println!("Rust executed the loop {} times in 10 seconds. ({}/sec)",
             format_with_commas(count_loops),
             format_with_commas(per_second));
}

fn format_with_commas(num: u64) -> String {
    let num_str = num.to_string();
    let mut result = String::new();
    
    // Reverse the string and insert commas every three digits
    for (i, ch) in num_str.chars().rev().enumerate() {
        if i % 3 == 0 && i != 0 {
            result.push(',');
        }
        result.push(ch);
    }
    
    // The string is reversed at this point, so reverse again to correct it
    result.chars().rev().collect()
}
